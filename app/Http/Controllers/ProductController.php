<?php

namespace App\Http\Controllers;

use App\Models\ProductPosition;
use App\Models\SubCategory;
use App\Repositories\BannerRepository;
use App\Repositories\BundleRepository;
use App\Repositories\CartRepository;
use App\Repositories\SubCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index(BannerRepository $bannerRepository, SubCategoryRepository $subcategoryRepository, BundleRepository $bundleRepository)
    {
        $banners = $bannerRepository->get();
        $subcategories = $subcategoryRepository->get();
        $bundles = $bundleRepository->getBundleProduct();
        return view('frontend.product', compact('banners', 'subcategories', 'bundles'));
    }

    public function view(BundleRepository $bundleRepository, string $meta)
    {

        $products = [];

        $bundle = $bundleRepository->getBundleByUUID($meta);

        // return $bundle;

        // return json_decode($bundle->subcategories);

        // $positions = ProductPosition::whereIn('sub_category_id', json_decode($bundle->subcategories))
        //     ->with('images')
        //     ->get();



        // return $bundle;

        $subcategories = SubCategory::whereIn('id', json_decode($bundle->subcategories))->with('productposition', function($position){
            $position->with('images');
        })->get();

        // return $subcategories;

        // return $positions;

        return view('frontend.choose-product', compact('bundle', 'meta', 'subcategories'));
    }

    public function store(Request $request, CartRepository $cartRepository)
    {

       $input = $request->all();
        DB::beginTransaction();
//dd( $input);
        try {

            if ($request->has('cartId')) {
                $sessionId = $cartRepository->updateCartContents($input);
            } else {
                $sessionId = $cartRepository->saveCartContents($input);
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'data' => $sessionId,
                'message' => 'Cart inserted successfully'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'data' => '',
                'message' => $e->getMessage()
            ]);
        }
    }
}
