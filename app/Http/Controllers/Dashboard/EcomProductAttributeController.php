<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\EcomAttributeImage;
use App\Models\EcommerceAttribute;
use App\Models\Product;
use App\Traits\FileUploadTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EcomProductAttributeController extends Controller
{
    use FileUploadTraits;
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        try {
            $this->authorize('view ecommerce product attribute');
            $product = Product::findOrFail($id);
            $ecommerceAttributes = $product->ecommerce()->with('images', 'sizeValue', 'colorValue')->get();
            return view('dashboard.ecommerce-product.attributes.index', compact('ecommerceAttributes', 'product'));
        } catch (\Throwable $th) {
            Log::error('Ecommerce Product Attribute Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        try {
            $this->authorize('create ecommerce product attribute');
            $product = Product::findOrFail($id);
            $sizeAttributes = Attribute::with('attributeValues')->findOrFail(1);
            $colorAttributes = Attribute::with('attributeValues')->findOrFail(2);
            return view('dashboard.ecommerce-product.attributes.create', compact('product', 'sizeAttributes', 'colorAttributes'));
        } catch (\Throwable $th) {
            Log::error('Ecommerce Product Attribute Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $this->authorize('create ecommerce product attribute');
        $validator = Validator::make($request->all(), [
            'size_value_id' => 'required|exists:attribute_values,id',
            'color_value_id' => 'required|exists:attribute_values,id',
            'price' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:0',
            'images' => 'required|array',
            'image.*' => 'file|max_size',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $product = Product::findOrFail($id);

            $ecomData = [
                'product_id' => $product->id,
                'size_id' => 1,
                'color_id' => 2,
                'size_value_id' => $request['size_value_id'],
                'color_value_id' => $request['color_value_id'],
                'price' => $request['price'],
                'quantity' => $request['quantity'],
            ];
            $ecommerceAttribute = $product->ecommerce()->create($ecomData);

            if (!empty($request['images']) && is_array($request['images'])) {
                foreach ($request['images'] as $imageFile) {
                    if ($imageFile->isValid()) {
                        $file = $this->uploadFile($imageFile, "ecommerce");

                        EcomAttributeImage::create([
                            'ecommerce_attribute_id' => $ecommerceAttribute->id,
                            'image' => $file['file_path'],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('dashboard.ecommerce-product-attributes.index', $id)->with('success', 'Ecommerce Product Attribute Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Ecommerce Product Attribute Store Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('update ecommerce product attribute');
            $ecommerceAttribute = EcommerceAttribute::with('images')->findOrFail($id);
            $sizeAttributes = Attribute::with('attributeValues')->findOrFail(1);
            $colorAttributes = Attribute::with('attributeValues')->findOrFail(2);
            return view('dashboard.ecommerce-product.attributes.edit', compact('ecommerceAttribute', 'sizeAttributes', 'colorAttributes'));
        } catch (\Throwable $th) {
            Log::error('Ecommerce Product Attribute Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update ecommerce product attribute');
        $validator = Validator::make($request->all(), [
            'size_value_id' => 'required|exists:attribute_values,id',
            'color_value_id' => 'required|exists:attribute_values,id',
            'price' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'image.*' => 'file|max_size',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $ecommerceAttribute = EcommerceAttribute::findOrFail($id);
            $ecommerceAttribute->size_value_id = $request->size_value_id;
            $ecommerceAttribute->color_value_id = $request->color_value_id;
            $ecommerceAttribute->price = $request->price;
            $ecommerceAttribute->quantity = $request->quantity;
            $ecommerceAttribute->save();

            if (!empty($request['images']) && is_array($request['images'])) {
                if ($ecommerceAttribute->images) {
                    foreach ($ecommerceAttribute->images as $itemImage) {
                        if ($itemImage->image && Storage::exists($itemImage->image)) {
                            Storage::delete($itemImage->image);
                        }
                        $itemImage->delete();
                    }
                }
                foreach ($request['images'] as $imageFile) {
                    if ($imageFile->isValid()) {
                        $file = $this->uploadFile($imageFile, "ecommerce");

                        EcomAttributeImage::create([
                            'ecommerce_attribute_id' => $ecommerceAttribute->id,
                            'image' => $file['file_path'],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('dashboard.ecommerce-product-attributes.index', $ecommerceAttribute->product_id)->with('success', 'Ecommerce Product Attribute Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Ecommerce Product Attribute Update Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('delete ecommerce product attribute');
            $ecommerceAtrribute = EcommerceAttribute::findOrFail($id);
            if ($ecommerceAtrribute->images) {
                foreach ($ecommerceAtrribute->images as $itemImage) {
                    if ($itemImage->image && Storage::exists($itemImage->image)) {
                        Storage::delete($itemImage->image);
                    }
                    $itemImage->delete();
                }
            }
            $ecommerceAtrribute->delete();
            return redirect()->back()->with('success', 'Ecommerce Product Attribute Deleted Successfully!');
        } catch (\Throwable $th) {
            Log::error('Ecommerce Product Attribute Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
