<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductExtraCost;
use App\Models\SubCategory;
use App\Traits\FileUploadTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class KitProductController extends Controller
{
    use FileUploadTraits;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view kit product');
            $kitProducts = Product::with('category','subcategory')->where('product_type', 1)->get();
            return view('dashboard.kit-product.index',compact('kitProducts'));
        } catch (\Throwable $th) {
            Log::error('Kit Product Index Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('create kit product');
            $categories = Category::where('status', 1)->get();
            $subcategories = SubCategory::where('status', 1)->get();
            $sizeAttributes = Attribute::with('attributeValues')->findOrFail(1);
            $colorAttributes = Attribute::with('attributeValues')->findOrFail(2);
            return view('dashboard.kit-product.create', compact('categories','subcategories','sizeAttributes','colorAttributes'));
        } catch (\Throwable $th) {
            Log::error('Kit Product Add Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->authorize('create kit product');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'mrp' => 'required|integer',
            'price' => 'required|integer',
            'no_of_stock' => 'required|integer',
            'status' => 'required|in:1,0',
            'size_id' => 'required|array',
            'size_id.*' => 'exists:attribute_values,id',
            'color_id' => 'required|array',
            'color_id.*' => 'exists:attribute_values,id',
            'image' => 'nullable|array',
            'image.*' => 'image|max_size',
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
            $product->mrp = $request->mrp;
            $product->price = $request->price;
            $product->no_of_stock = $request->no_of_stock;
            $product->product_type = 1;
            $product->save();

            if (isset($request->size_id) && !empty($request->size_id)) {
                foreach ($request->size_id as $k => $item) {
                    $extraCost = ProductExtraCost::where(['sub_category_id' => $request->subcategory_id, 'attribute_value_id' => $item])->first();
                    $productAttr =  ProductAttribute::create([
                        'product_id' => $product->id,
                        'attribute_id' => 1,
                        'value' => $item,
                        'extra_cost' => $extraCost->amount ?? 0.00
                    ]);
                }
            }
            if (isset($request->color_id) && !empty($request->color_id)) {
                foreach ($request->color_id as $k => $item) {
                    $extraCost = ProductExtraCost::where(['sub_category_id' => $request->subcategory_id, 'attribute_value_id' => $item])->first();
                    $productAttr =  ProductAttribute::create([
                        'product_id' => $product->id,
                        'attribute_id' => 2,
                        'value' => $item,
                        'extra_cost' => $extraCost->amount ?? 0.00
                    ]);
                    if (!empty($request->image[$k])) {
                        $file = $this->uploadFile($request->image[$k], "products");
                        Log::info('File uploaded successfully', ['file' => $file]);
                        // Media::create([
                        //         'table_name' => 'product_attributes',
                        //         'table_id' =>  $productAttr->id,
                        //         'path' => $file['file_path'],
                        //         'file_name' => $file['file_name'],
                        // ]);
                        $productAttr->image = $file['file_path'];
                        $productAttr->save();
                    }
                }
            }

            DB::commit();
            return redirect()->route('dashboard.kit-products.index')->with('success', 'Kit Product Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Kit Product Store Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('update kit product');
            $kitProduct = Product::with('size','color')->findOrFail($id);
            $productSizeAttributes = ProductAttribute::where('product_id', $id)->where('attribute_id', 1)->get()->pluck('value')->toArray();
            $productColorAttributes = ProductAttribute::where('product_id', $id)->where('attribute_id', 2)->get()->pluck('value')->toArray();
            $categories = Category::where('status', 1)->get();
            $subcategories = SubCategory::where('status', 1)->get();
            $sizeAttributes = Attribute::with('attributeValues')->findOrFail(1);
            $colorAttributes = Attribute::with('attributeValues')->findOrFail(2);
            return view('dashboard.kit-product.edit', compact('productSizeAttributes','productColorAttributes','kitProduct','categories','subcategories','sizeAttributes','colorAttributes'));
        } catch (\Throwable $th) {
            throw $th;
            Log::error('Kit Product Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $this->authorize('update kit product');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:products,slug,' . $id,
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'mrp' => 'required|integer',
            'price' => 'required|integer',
            'no_of_stock' => 'required|integer',
            'status' => 'required|in:1,0',
            'size_id' => 'required|array',
            'size_id.*' => 'exists:attribute_values,id',
            'color_id' => 'nullable|array',
            'color_id.*' => 'exists:attribute_values,id',
            'image' => 'nullable|array',
            'image.*' => 'image|max_size',
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
            $product->mrp = $request->mrp;
            $product->price = $request->price;
            $product->no_of_stock = $request->no_of_stock;
            $product->save();

            if (isset($request->size_id) && !empty($request->size_id)) {
                $productSizeAttributes = ProductAttribute::where('product_id', $id)->where('attribute_id', 1)->get();
                foreach ($productSizeAttributes as $sizeitem) {
                    $sizeitem->delete();
                }
                foreach ($request->size_id as $k => $item) {
                    $extraCost = ProductExtraCost::where(['sub_category_id' => $request->subcategory_id, 'attribute_value_id' => $item])->first();
                    $productAttr =  ProductAttribute::create([
                        'product_id' => $product->id,
                        'attribute_id' => 1,
                        'value' => $item,
                        'extra_cost' => $extraCost->amount ?? 0.00
                    ]);
                }
            }
            if (isset($request->color_id) && !empty($request->color_id)) {
                foreach ($request->color_id as $k => $item) {
                    Log::info('Color ID', ['color_id' => $item]);
                    $extraCost = ProductExtraCost::where(['sub_category_id' => $request->subcategory_id, 'attribute_value_id' => $item])->first();
                    $productAttr =  ProductAttribute::create([
                        'product_id' => $product->id,
                        'attribute_id' => 2,
                        'value' => $item,
                        'extra_cost' => $extraCost->amount ?? 0.00
                    ]);
                    if (!empty($request->image[$k])) {
                        $file = $this->uploadFile($request->image[$k], "products");
                        Log::info('File uploaded successfully', ['file' => $file]);
                        // Media::create([
                        //         'table_name' => 'product_attributes',
                        //         'table_id' =>  $productAttr->id,
                        //         'path' => $file['file_path'],
                        //         'file_name' => $file['file_name'],
                        // ]);
                        $productAttr->image = $file['file_path'];
                        $productAttr->save();
                    }
                }
            }


            DB::commit();
            return redirect()->route('dashboard.kit-products.index')->with('success', 'Kit Product Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Kit Product Update Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('delete kit product');
            $product = Product::with('attributes')->findOrFail($id);
            $productAtrributes = ProductAttribute::where('product_id', $id)->get();
            if ($productAtrributes) {
                foreach ($productAtrributes as $item) {
                    if ($item->image && Storage::exists($item->image)) {
                        Storage::delete($item->image);
                    }
                    $item->delete();
                }
            }
            $product->delete();
            return redirect()->back()->with('success', 'Product Deleted Successfully!');
        } catch (\Throwable $th) {
            Log::error('Product Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    public function deleteProductColorAttr(string $id)
    {
        try {
            $this->authorize('update kit product');
            $productAttribute = ProductAttribute::findOrFail($id);
            // Delete image from storage if exists
            if ($productAttribute->image && Storage::exists($productAttribute->image)) {
                Storage::delete($productAttribute->image);
            }

            // Delete record from DB
            $productAttribute->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product Color Attribute Deleted Successfully!'
            ]);
        } catch (\Throwable $th) {
            Log::error('Product Delete Failed', ['error' => $th->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => "Something went wrong! Please try again later"
            ], 500);
            throw $th;
        }
    }
}
