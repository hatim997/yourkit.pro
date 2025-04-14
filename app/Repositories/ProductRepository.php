<?php

namespace App\Repositories;

use App\Models\Media;
use App\Models\Product;
use Illuminate\Support\Arr;
use App\Models\ProductAttribute;
use App\Models\ProductExtraCost;
use App\Models\SubCategory;
use App\Traits\FileUploadTraits;
use App\Utils\Helper;
use Illuminate\Support\Facades\Storage;

class ProductRepository extends BaseRepository
{

    use FileUploadTraits;
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function createProductWithAttributes(array $data)
    {


        $input   = Arr::only($data, ['name', 'description', 'category_id', 'sub_category_id', 'mrp', 'price', 'no_of_stock', 'status']);
        $product = $this->create($data);

        // return $product->attributes;
        if (isset($data['attribute_name'])) {
            foreach ($data['attribute_name'] as $key => $value) {
                if (isset($data[$value]) && !empty($data[$value])) {

                    // This Loop is For To Add Attribute Values AS Per Attribute Names.
                    foreach ($data[$value] as $k => $item) {

                        $extraCost = ProductExtraCost::where(['sub_category_id' => $input['sub_category_id'], 'attribute_value_id' => $item['value']])->first();

                        $productAttr =  ProductAttribute::create([
                            'product_id' => $product->id,
                            'attribute_id' => $data['attribute_id'][$key],
                            'value' => $item['value'],
                            'extra_cost' => $extraCost->amount ?? 0.00
                        ]);
                        // $product->attributes()->attach($data['attribute_id'][$key],  ['value' =>  $item['value']]);
                        if (!empty($item['image'])) {
                            $file = $this->uploadFile($item['image'], "products");
                            // Media::create([
                            //         'table_name' => 'product_attributes',
                            //         'table_id' =>  $productAttr->id,
                            //         'path' => $file['file_path'],
                            //         'file_name' => $file['file_name'],
                            // ]);
                            $productAttr->update(['image' => $file['file_path']]);
                        }
                    }
                }
            }
        }

        return $product;
    }

    public function updateProductWithAttributes(array $data, $id)
    {
        $product = Product::find($id);
        $attrValues =  $product?->attributes?->pluck('pivot')->toArray();
        $old_image='';

        $input   = Arr::only($data, ['name', 'description', 'category_id', 'sub_category_id', 'mrp', 'price', 'no_of_stock', 'status']);
        // return $data;
// dd($data);
        $attrValuesToBeDeleted = $attrValues;
        if (isset($data['attribute_name'])) {
            foreach ($data['attribute_name'] as $key => $value) {
                if (isset($data[$value]) && !empty($data[$value])) {


                    // This Loop is to Go Through The Attribute Values AS Per Attribute Names.
                    foreach (array_values($data[$value]) as $k => $item) {



                        if (Helper::keyValueExists($attrValues, 'value', $item['value'])) {

                            //  if($key == 1){
                            //     return $item;
                            //  }

                            // If Given Attribute Value Same with attrValuesToBeDeleted Value Then Unset The Value From It.
                            foreach ($attrValues as $index => $row) {


                                // $productAttr = ProductAttribute::find($row['id']);

                                if ($row['value'] == $item['value']) {
                                    $productAttr = ProductAttribute::find($row['id']);
// dd( $productAttr);
                                    // if (!empty($item['image'])) {

                                    //     if (is_file(public_path('storage/' . $productAttr->image))) {
                                    //         unlink(public_path('storage/' . $productAttr->image));
                                    //     }
                                    //     $file = $this->uploadFile($item['image'], "products");
                                    //     $productAttr->update(['image' => $file['file_path']]);

                                    //     // Delete the file from storage


                                    // }

                                    if (!empty($item['image']) ) {
                                        // Only delete the existing image if a new one is uploaded
                                        if (!empty($productAttr->image) && is_file(public_path('storage/' . $productAttr->image))) {
                                            unlink(public_path('storage/' . $productAttr->image));
                                        }
                                    
                                        $file = $this->uploadFile($item['image'], "products");
                                        $productAttr->update(['image' => $file['file_path']]);
                                    } else {
                                       
                                        $productAttr->update(['image' => $productAttr->image]);
                                    }
                                    
                                    

                                    $extraCost = ProductExtraCost::where(['sub_category_id' => $input['sub_category_id'], 'attribute_value_id' => $item['value']])->first();
                                    $productAttr->update(['extra_cost' => $extraCost->amount ?? 0.00]);

                                    unset($attrValuesToBeDeleted[$index]);
                                }
                            }
                        } else { // If Not Exist The Given Attribute Value Then Need To Be Added.

                            $extraCost = ProductExtraCost::where(['sub_category_id' => $input['sub_category_id'], 'attribute_value_id' => $item['value']])->first();

                             //dd($item);

                            $productAttr =  ProductAttribute::create([
                                'product_id' => $product->id,
                                'attribute_id' => $data['attribute_id'][$key],
                                'value' => $item['value'],
                                'extra_cost' => $extraCost->amount ?? 0.00,
                                'image' => isset($item['old_image'])?$item['old_image']:''

                            ]);
                            //dd(  $productAttr);
                            if (!empty($item['image'])) {

                                $file = $this->uploadFile($item['image'], "products");
                                $productAttr->update(['image' => $file['file_path']]);
                            } 
                        }
                    }
                }
            }


            $idsTobeDeleted = array_values($attrValuesToBeDeleted);

            if (count($idsTobeDeleted) > 0) {
                $ids = Arr::pluck($idsTobeDeleted, 'id');
                foreach ($ids as $key => $id) {
                    $productAttribute = ProductAttribute::find($id);
                    $productAttribute->delete();
                }
            }
        }
        $product->update($input);
        return $product;
    }

    public function deleteProductWithAttribute(string $id)
    {

        $product = $this->findOrFail($id);
        $product->ownattribute()->detach();
        return $product->delete();
    }
}
