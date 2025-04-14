<?php

namespace App\Repositories;

use App\Models\AttributeValue;
use App\Models\EcomAttributeImage;
use App\Models\Product;
use App\Traits\FileUploadTraits;

class EcommerceRepository extends BaseRepository
{

    use FileUploadTraits;

    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getProduct()
    {
        return $this->model->where(['product_type' => 2, 'status' => 1])->with('ecommerce')->get();
    }

    public function getProductByUUID(string $productId)
    {

        $color = [];
        $size = [];

        $product = $this->model->where('productId', $productId)->first();
        //dd($product->ecommerce);
        foreach ($product->ecommerce as $ecommerce) {

            $colorValue = AttributeValue::where('id', $ecommerce->color_value_id)->first();
            $sizeValue = AttributeValue::where('id', $ecommerce->size_value_id)->first();
            $ecommerce->color_value = $colorValue->value;
            $ecommerce->size_value = $sizeValue->value;

            if (!in_array($colorValue->value, $color)) {
                $color[] = $colorValue->value;
            }
            if (!in_array($sizeValue->value, $size)) {
                $size[] = $sizeValue->value;
            }
        }

        $product->color = $color;
        $product->size = $size;


        return $product;
    }

    // public function storeProduct(array $data)
    // {
    //     $data['product_type'] = 2;
    //     $product = $this->model->create($data);
    //     // dd($data);

    //     foreach ($data['attribute'] as $arrtibute) {
    //         // dd($attribute);
    //         $ecomData = [
    //             'product_id' => $product->id,
    //             'size_id' => 1,
    //             'color_id' => 2,
    //             'size_value_id' => $arrtibute['size'],
    //             'color_value_id' => $arrtibute['color'],
    //             'price' => $arrtibute['price'],
    //             'quantity' => $arrtibute['quantity'],

    //         ];
    //         if (!empty($arrtibute['image']) && is_array($arrtibute['image'])) {
    //             $imagePaths = [];

    //             foreach ($arrtibute['image'] as $imageFile) {
    //                 if ($imageFile->isValid()) {

    //                     $file = $this->uploadFile($imageFile, "ecommerce");


    //                     $imagePaths[] = $file['file_path'];
    //                 }
    //             }


    //             $ecomData['image'] = json_encode($imagePaths);
    //         }
    //         // if (!empty($arrtibute['image'])) {
    //         //     $file = $this->uploadFile($arrtibute['image'], "ecommerce");
    //         //     //$product->ecommerce()->update(['image' => $file['file_path']]);
    //         //     $ecomData['image'] = $file['file_path'];
    //         // }

    //         $product->ecommerce()->create($ecomData);
    //     }

    //     $product->productId = generateUUID();
    //     $product->save();

    //     return $product;
    // }

    public function storeProduct(array $data)
{
    $data['product_type'] = 2;
    $product = $this->model->create($data);
    if (!empty($data['size_chart']) && $data['size_chart']->isValid()) {
        $sizeChartFile = $this->uploadFile($data['size_chart'], "size_charts");
        $product->size_chart = $sizeChartFile['file_path']; 
    }
    foreach ($data['attribute'] as $arrtibute) {
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

    return $product;
}

}
