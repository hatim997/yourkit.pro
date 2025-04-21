<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SubCategory;
use App\Repositories\EcommerceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EcommerceController extends Controller
{
    protected $ecommerceRepository;

    public function __construct(EcommerceRepository $ecommerceRepository)
    {
        $this->ecommerceRepository = $ecommerceRepository;
    }

    public function index()
    {
        $products = $this->ecommerceRepository->getProduct();
        $subcategories = SubCategory::where('status', 1)->orderByDesc('created_at')->get();
        return view('frontend.ecommerce', compact('products', 'subcategories'));
    }

    public function categoryIndex($id)
    {

        $cat = SubCategory::where('slug', $id)->first();
        $products = Product::where(['product_type' => 2, 'status' => 1, 'sub_category_id' => $cat->id])->with('ecommerce')->get();
        $subcategories = SubCategory::where('status', 1)->orderByDesc('created_at')->get();
        return view('frontend.ecommerce-category', compact('products', 'subcategories'));
    }

    public function details($meta)
    {
        $product = $this->ecommerceRepository->getProductByUUID($meta);
        // return $product;
        return view('frontend.ecommerce-details', compact('product'));
    }

    public function submit(Request $request)
    {
        return $request->all();
    }


    public function fetchAttributes(Request $request)
    {
        $productId = $request->product_id;
        $color = $request->color;
        $size = $request->size;

        // $attribute = \DB::table('ecommerce_attributes')
        //     ->join('attribute_values as av1', 'ecommerce_attributes.color_value_id', '=', 'av1.id')
        //     ->join('attribute_values as av2', 'ecommerce_attributes.size_value_id', '=', 'av2.id')
        //     ->where('ecommerce_attributes.product_id', $productId) // Filter by product_id
        //     ->where('av1.value', $color)
        //     ->where('av2.value', $size)
        //     ->select('ecommerce_attributes.price', 'ecommerce_attributes.image')
        //     ->first();

        // if ($attribute) {
        //     return response()->json([
        //         'success' => true,
        //         'price' => $attribute->price,
        //         'image' => url('storage/' . $attribute->image),
        //         'img'=>$attribute->image,
        //     ]);
        // }
        $attribute = DB::table('ecommerce_attributes')
            ->join('attribute_values as av1', 'ecommerce_attributes.color_value_id', '=', 'av1.id')
            ->join('attribute_values as av2', 'ecommerce_attributes.size_value_id', '=', 'av2.id')
            ->join('ecom_attribute_images', 'ecom_attribute_images.ecommerce_attribute_id', '=', 'ecommerce_attributes.id')
            ->where('ecommerce_attributes.product_id', $productId)
            ->where('av1.value', $color)
            ->where('av2.value', $size)
            ->select(
                'ecommerce_attributes.price',
                'ecom_attribute_images.image as attribute_image'
            )
            ->get();

        if ($attribute->isNotEmpty()) {
            $images = $attribute->pluck('attribute_image')->map(function ($image) {
                return url('storage/' . $image);
            });

            return response()->json([
                'success' => true,
                'price' => $attribute->first()->price,
                'images' => $images,
                'color' => $color,
                'size' => $size,
            ]);
        }
        if ($attribute->isNotEmpty()) {
            $images = $attribute->pluck('attribute_image')->map(function ($image) {
                return url('storage/' . $image);
            });

            return response()->json([
                'success' => true,
                'price' => $attribute->first()->price,
                'images' => $images,
                'color' => $color,
                'size' => $size,
            ]);
        }

        return response()->json(['success' => false]);
    }
}
