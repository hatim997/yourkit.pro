<?php

namespace App\Repositories;

use App\Models\AttributeValue;
use App\Models\Bundle;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductBundle;
use App\Traits\FileUploadTraits;
use Illuminate\Http\Request;

class BundleRepository extends BaseRepository
{

    use FileUploadTraits;

    public function __construct(Bundle $model)
    {
        parent::__construct($model);
    }

    public function getBundleProduct($limit = 0)
    {
        // $bundle = Bundle::with(['products' => function($query) {
        //     $query->where('products.status', '1');
        // }]);
        $bundle = Bundle::with(['products' => function($query) {
            $query->where('products.status', '1');
        }])
        ->where('status', 1) // Apply status filter on the query
        ->orderBy('position', 'asc');
        if ($limit > 0) {
            $bundle->limit($limit);
        }
        return $bundle->where('status',1)->get();
    }

    public function createBundle(array $data)
    {
        $bundle = $this->create($data);
        $bundle->products()->attach($data['details']);

        return $bundle;
    }

    public function updateBundle(string $id, array $data)
    {
        $bundle = Bundle::findOrFail($id);
        $bundle->update($data);
        $bundle->products()->sync($data['details']);

        return $bundle;
    }

    public function deleteBundle(string $id)
    {
        $bundle = Bundle::findOrFail($id);
        $bundle->products()->detach();
        $bundle->delete();

        return $bundle;
    }

    public function getBundleByUUID(string $uuid)
    {

        $bundle = Bundle::where('uuid', $uuid)
        ->with(['products' => function ($query) {
            $query->where('products.status', 1)
                  ->with(['color', 'size']);
        }])
        ->first();

        if ($bundle) {
            foreach ($bundle->products as $product) {
                foreach ($product->color as $color) {
                    $colorValue = AttributeValue::where('id', $color->pivot->value)->first();
                    //dd(  $colorValue);
                    $color->value = $colorValue->value ?? '';
                    $color->attr_id = $colorValue->id ?? '';
                    $color->image = $color->pivot->image ?? '';
                }

                foreach ($product->size as $size) {
                    $sizeValue = AttributeValue::where('id', $size->pivot->value)->first();
                    $size->attr_id = $sizeValue->id;
                    $size->value = $sizeValue->value;
                    $size->extra_cost = $size->pivot->extra_cost ?? 0.00;
                }
            }
        }

        return $bundle;
    }

    // Helper method to extract attributes from a product's ownattribute
    private function extractAttributesByType($attributes, $type)
    {
        return $attributes->where('type', $type)->values();
    }

    public function createProductBundleWithImage(Request $input)
    {

        $subCategoryArray = [];

        $data = $input->all();
        $bundle = $this->create($data);

        if ($input->has('image')) {
            $file = $this->uploadFile($data['image'], "bundles");
            $bundle->update(['image' => $file['file_path']]);
        }

        if(isset($input->products) && !empty($input->products)){
            foreach($input->products as  $item){

                $product = Product::where('id', $item['product_id'])->first();
                $subCategoryArray[] = $product->sub_category_id;

                ProductBundle::create([
                    'product_id' => $item['product_id'],
                    'bundle_id' => $bundle->id,
                    'quantity' => $item['quantity']
                ]);
            }
        }

        $bundle->update(['uuid' => generateUUID(), 'subcategories' => json_encode($subCategoryArray)]);

        return $bundle;
    }

    public function updateProductBundleWithImage($id, Request $input)
    {

        $subCategoryArray = [];

        $data = $input->all();

        // dd($data);

        $bundle = $this->model->findOrFail($id);
        $bundle->update($data);

        if ($input->has('image')) {
            $file = $this->uploadFile($data['image'], "bundles");
            $bundle->update(['image' => $file['file_path']]);
        }

        ProductBundle::where('bundle_id', $bundle->id)->delete();

        if(isset($input->products) && !empty($input->products)){
            foreach($input->products as  $item){

                $product = Product::where('id', $item['product_id'])->first();
                $subCategoryArray[] = $product->sub_category_id;

                ProductBundle::create([
                    'product_id' => $item['product_id'],
                    'bundle_id' => $bundle->id,
                    'quantity' => $item['quantity']
                ]);
            }
        }

        $bundle->update(['subcategories' => json_encode($subCategoryArray)]);
        return $bundle;

    }
}
