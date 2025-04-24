<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\EcomAttributeImage;
use App\Models\EcommerceAttribute;
use App\Models\Product;
use App\Models\ProductVolumeDiscount;
use App\Models\SubCategory;
use App\Traits\FileUploadTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EcommerceProductController extends Controller
{
    use FileUploadTraits;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view ecommerce product');
            $ecomProducts = Product::with('category','subcategory')->where('product_type', 2)->get();
            return view('dashboard.ecommerce-product.index',compact('ecomProducts'));
        } catch (\Throwable $th) {
            Log::error('Ecommerce Product Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $this->authorize('create ecommerce product');
            $categories = Category::where('status', 1)->get();
            $subcategories = SubCategory::where('status', 1)->get();
            $sizeAttributes = Attribute::with('attributeValues')->findOrFail(1);
            $colorAttributes = Attribute::with('attributeValues')->findOrFail(2);
            return view('dashboard.ecommerce-product.create', compact('categories','subcategories','sizeAttributes','colorAttributes'));
        } catch (\Throwable $th) {
            Log::error('Ecommerce Product Add Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create ecommerce product');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'status' => 'required|in:1,0',
            'size_chart' => 'required|file|max_size',
            'quantity' => 'nullable|array',
            'quantity.*' => 'integer|min:0',
            'discount_percentage' => 'nullable|array',
            'discount_percentage.*' => 'integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $product = new Product();
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->status = $request->status;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->subcategory_id;
            $product->product_type = 2;
            if (isset($request->size_chart) && !empty($request->size_chart)) {
                $sizeChartFile = $this->uploadFile($request['size_chart'], "size_charts");
                $product->size_chart = $sizeChartFile['file_path'];
                Log::info('File uploaded successfully', ['file' => $sizeChartFile]);
            }

            $product->save();

            foreach ($request['attribute'] as $arrtibute) {
                $ecomData = [
                    'product_id' => $product->id,
                    'size_id' => 1,
                    'color_id' => 2,
                    'size_value_id' => $arrtibute['size'],
                    'color_value_id' => $arrtibute['color'],
                    'price' => $arrtibute['price'],
                    'quantity' => $arrtibute['quantity'],
                ];

                $ecommerceAttribute = $product->ecommerce()->create($ecomData);


                if (!empty($arrtibute['image']) && is_array($arrtibute['image'])) {
                    $imagePaths = [];

                    foreach ($arrtibute['image'] as $imageFile) {
                        if ($imageFile->isValid()) {
                            $file = $this->uploadFile($imageFile, "ecommerce");

                            EcomAttributeImage::create([
                                'ecommerce_attribute_id' => $ecommerceAttribute->id,
                                'image' => $file['file_path'],
                            ]);
                        }
                    }
                }
            }

            $product->productId = generateUUID();
            $product->save();

            if(isset($request->quantity) && count($request->quantity) > 0)
            {
                foreach ($request->quantity as $key => $quantity) {
                    $productVolumeDiscount = new ProductVolumeDiscount();
                    $productVolumeDiscount->product_id = $product->id;
                    $productVolumeDiscount->quantity = $quantity;
                    $productVolumeDiscount->discount_percentage = $request->discount_percentage[$key] ?? '0';
                    $productVolumeDiscount->save();
                }
            }

            DB::commit();
            return redirect()->route('dashboard.ecommerce-products.index')->with('success', 'Ecommerce Product Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Ecommerce Product Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $this->authorize('update ecommerce product');
            $categories = Category::where('status', 1)->get();
            $subcategories = SubCategory::where('status', 1)->get();
            $product = Product::with('ecommerce','productVolumeDiscounts')->findOrFail($id);
            return view('dashboard.ecommerce-product.edit', compact('categories','subcategories','product'));
        } catch (\Throwable $th) {
            Log::error('Ecommerce Product Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update ecommerce product');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug,'.$id,
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'status' => 'required|in:1,0',
            'size_chart' => 'nullable|file|max_size',
            'quantity' => 'nullable|array',
            'quantity.*' => 'integer|min:0',
            'discount_percentage' => 'nullable|array',
            'discount_percentage.*' => 'integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->status = $request->status;
            $product->category_id = $request->category_id;
            $product->sub_category_id = $request->subcategory_id;
            if (isset($request->size_chart) && !empty($request->size_chart)) {
                if ($product->size_chart && Storage::exists($product->size_chart)) {
                    Storage::delete($product->size_chart);
                }
                $sizeChartFile = $this->uploadFile($request['size_chart'], "size_charts");
                $product->size_chart = $sizeChartFile['file_path'];
                Log::info('File uploaded successfully', ['file' => $sizeChartFile]);
            }
            $product->save();

            if(isset($request->quantity) && count($request->quantity) > 0)
            {
                ProductVolumeDiscount::where('product_id', $product->id)->delete();
                foreach ($request->quantity as $key => $quantity) {
                    $productVolumeDiscount = new ProductVolumeDiscount();
                    $productVolumeDiscount->product_id = $product->id;
                    $productVolumeDiscount->quantity = $quantity;
                    $productVolumeDiscount->discount_percentage = $request->discount_percentage[$key] ?? '0';
                    $productVolumeDiscount->save();
                }
            }

            DB::commit();
            return redirect()->route('dashboard.ecommerce-products.index')->with('success', 'Ecommerce Product Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Ecommerce Product Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->authorize('delete ecommerce product');
            $product = Product::with('ecommerce')->findOrFail($id);
            $ecommerceAtrributes = EcommerceAttribute::where('product_id', $id)->get();
            if ($ecommerceAtrributes) {
                foreach ($ecommerceAtrributes as $item) {
                    if ($item->images) {
                        foreach ($item->images as $itemImage) {
                            if ($itemImage->image && Storage::exists($itemImage->image)) {
                                Storage::delete($itemImage->image);
                            }
                            $itemImage->delete();
                        }
                    }
                    $item->delete();
                }
            }
            if ($product->size_chart && Storage::exists($product->size_chart)) {
                Storage::delete($product->size_chart);
            }
            $product->delete();
            return redirect()->back()->with('success', 'Product Deleted Successfully!');
        } catch (\Throwable $th) {
            Log::error('Product Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
