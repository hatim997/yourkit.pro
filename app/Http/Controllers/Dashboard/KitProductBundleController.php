<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Product;
use App\Models\ProductBundle;
use App\Traits\FileUploadTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KitProductBundleController extends Controller
{
    use FileUploadTraits;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view kit product bundle');
            $bundles = Bundle::get();
            return view('dashboard.kit-product-bundle.index', compact('bundles'));
        } catch (\Throwable $th) {
            Log::error('Kit Product Bundle Index Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('create kit product bundle');
            $products = Product::where('product_type', 1)->get();
            return view('dashboard.kit-product-bundle.create', compact('products'));
        } catch (\Throwable $th) {
            Log::error('Kit Product Bundle Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create kit product bundle');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'status' => 'required|in:1,0',
            'image' => 'required|file|max_size',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $bundle = new Bundle();
            $bundle->name = $request->name;
            $bundle->price = $request->price;
            $bundle->discount_percentage = $request->discount_percentage;
            $bundle->status = $request->status;
            $bundle->subcategories = '[]';
            $bundle->max_quantity = '0';
            if ($request->hasFile('image')) {
                $file = $this->uploadFile($request['image'], "bundles");
                $bundle->image = $file['file_path'];
            }
            $bundle->save();

            $subCategoryArray = [];
            if (isset($request->products) && !empty($request->products)) {
                foreach ($request->products as  $item) {

                    $product = Product::where('id', $item['product_id'])->first();
                    $subCategoryArray[] = $product->sub_category_id;

                    ProductBundle::create([
                        'product_id' => $item['product_id'],
                        'bundle_id' => $bundle->id,
                        'quantity' => $item['quantity']
                    ]);
                }
            }

            $bundle->uuid = generateUUID();
            $bundle->subcategories = json_encode($subCategoryArray);
            $bundle->save();

            DB::commit();
            return redirect()->route('dashboard.kit-product-bundles.index')->with('success', 'Kit Product Bundle Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Kit Product Bundle Store Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('update kit product bundle');
            $products = Product::where('product_type', 1)->get();
            $bundle = Bundle::with('products')->findOrFail($id);
            return view('dashboard.kit-product-bundle.edit', compact('products', 'bundle'));
        } catch (\Throwable $th) {
            Log::error('Kit Product Bundle Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $this->authorize('update kit product bundle');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'status' => 'required|in:1,0',
            'image' => 'mullable|file|max_size',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $bundle = Bundle::findOrFail($id);
            $bundle->name = $request->name;
            $bundle->price = $request->price;
            $bundle->discount_percentage = $request->discount_percentage;
            $bundle->status = $request->status;
            $bundle->subcategories = '[]';
            $bundle->max_quantity = '0';
            if ($request->hasFile('image')) {
                if ($bundle->image && Storage::exists($bundle->image)) {
                    Storage::delete($bundle->image);
                }
                $file = $this->uploadFile($request['image'], "bundles");
                $bundle->image = $file['file_path'];
            }
            $bundle->save();

            $subCategoryArray = [];
            if (isset($request->products) && !empty($request->products)) {
                if ($bundle->bundleProducts) {
                    foreach ($bundle->bundleProducts as $item) {
                        $item->delete();
                    }
                }
                foreach ($request->products as  $item) {
                    Log::info('Product ID ' . $item['product_id']);
                    $product = Product::where('id', $item['product_id'])->first();
                    Log::info('Product Data' . $product);
                    $subCategoryArray[] = $product->sub_category_id;

                    ProductBundle::create([
                        'product_id' => $item['product_id'],
                        'bundle_id' => $bundle->id,
                        'quantity' => $item['quantity']
                    ]);
                }
            }
            $bundle->subcategories = json_encode($subCategoryArray);
            $bundle->save();

            DB::commit();
            return redirect()->route('dashboard.kit-product-bundles.index')->with('success', 'Kit Product Bundle Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Kit Product Bundle Update Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('delete kit product bundle');
            $bundle = Bundle::with('products')->findOrFail($id);
            if ($bundle->products) {
                foreach ($bundle->products as $item) {
                    $item->delete();
                }
            }
            if ($bundle->image && Storage::exists($bundle->image)) {
                Storage::delete($bundle->image);
            }
            $bundle->delete();
            return redirect()->back()->with('success', 'Kit Product Bundle Deleted Successfully!');
        } catch (\Throwable $th) {
            Log::error('Kit Product Bundle Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
