<?php

namespace App\Repositories;

use App\Models\Bundle;
use App\Models\Cart;
use App\Models\CartContent;
use App\Models\CartContentAttribute;
use App\Models\Order;
use App\Models\PositionImage;
use App\Models\Product;
use App\Models\Tax;
use App\Utils\Helper;
use Illuminate\Support\Facades\Auth;

class CartRepository extends BaseRepository
{

    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }

    public function getCartCount($sessionId)
    {
        return Cart::where('sessionId', $sessionId)->count();
    }

    public function getCartDetails(string $sessionId)
    {
        $carts = Cart::where('sessionId', $sessionId)->with('contents.product')->get();

        // return $carts;

        // dd($carts);

        if (!empty($carts)) {
            foreach ($carts as $cart) {
                foreach ($cart->contents as $content) {
                    $positions = json_decode($content->positions, true);
                    // dd( $positions);
                    $position_image = [];
                    if (is_array($positions)) {
                        foreach ($positions as $position) {
                            if (is_array($position)) {
                                foreach ($position as $pos) {

                                    $image = PositionImage::where('id', $pos)->pluck('image')->first();
                                    $position_image[] = !is_null($image) ? url('assets/frontend/' . $image) : '';
                                }
                            } else {
                                $image = PositionImage::where('id', $position)->pluck('image')->first();
                                $position_image[] = !is_null($image) ? url('assets/frontend/' . $image) : '';
                            }
                        }
                    }
                    $content->position_images = $position_image;
                }
            }
        }


        return $carts;
    }
    
     public function getCartDetailsEdit(string $id){
        // return $this->findOrFail($id);
        return Cart::with(['bundle', 'contents'])->findOrFail($id);
    }

    public function saveCartContents(array $data)
    {
        $totalSizeCost = 0.00;
        $totalPositionCost = 0.00;
        $totalInfoDocCost = 0.00;
        $totalPositionCost = 0;


        /** insert cart data **/

        $cartId = generateUUID();

        $cartData = [
            'table' => $data['table'],
            'cartId' => $cartId,
            'sessionId' => $data['sessionId'],
            'table_id' => $data['table_id'],
            'comment' => $data['comment']
        ];

        $cart = $this->model->create($cartData);

        foreach ($data['product'] as $key => $product) {
            $sizeExtraCost = 0.00;

            $contentsData = [
                'color' => $product['color'],
                'attr_id' => $product['color_attribute_id'],
                'size' => $product['size']
            ];

            $cart->contents()->create([
                'cart_id' => $cart->id,
                'product_id' => $product['id'],
                'contents' => json_encode($contentsData),
                'positions' => isset($data['positions'][$product['type']]) ? json_encode($data['positions'][$product['type']]) : null
            ]);

            /** calculation for size **/

            if (!empty($product['size'])) {
                foreach ($product['size'] as $size) {
                    if (isset($size['extra_cost'])) {
                        $sizeExtraCost = $sizeExtraCost + ($size['quantity'] * $size['extra_cost']);
                    }
                }
            }

            /** calculation for size **/

            $totalSizeCost = $totalSizeCost + $sizeExtraCost;
        }

        /** calculation for position **/

        $bundle = Bundle::where('id', $data['table_id'])->first();

        if (!empty($data['position_count'])) {
           
            foreach ($bundle->products as $product) {
                $product_count = $data['position_count'][$product->sub_category_id];
                 //dd($product_count);
                if ($product_count > 0) {
                    $cost = ($product_count - 1) * 3;
                    $totalPositionCost += $product->pivot->quantity * $cost;
                }


            /** calculation for adding email **/

            if ((isset($data['is_phone_checked']) && $data['is_phone_checked'] == 'on')) {
                $cart->is_phone_checked = 1;
                $totalInfoDocCost += ($product->pivot->quantity *  2.00);
            }

            /** calculation for adding telephone **/

            if ((isset($data['is_email_checked']) && $data['is_email_checked'] == 'on')) {
                $cart->is_email_checked = 1;
                $totalInfoDocCost += ($product->pivot->quantity *  2.00);
            }
            }
        }


        /** total calculation (sizeCost + positionCost) **/

        $bundlePriceCost = $bundle->price ?? 0.00;
        $totalExtraCost = ($totalSizeCost + $totalPositionCost + $totalInfoDocCost);
        $totalCost = ($bundlePriceCost + $totalExtraCost);

        $cart->size_extra_cost = $totalSizeCost;
        $cart->position_extra_cost = $totalPositionCost;
        $cart->info_extra_cost = $totalInfoDocCost;
        $cart->total_extra_cost = $totalExtraCost;
        $cart->total_cost = $totalCost;
        $cart->cartId = $cartId;
        $cart->save();
        return $cart->sessionId;
    }

public function updateCartContents(array $data){
// dd($data);
        $totalSizeCost = 0.00;
        $totalPositionCost = 0.00;
        $totalInfoDocCost = 0.00;
        $totalPositionCost = 0;

        $cart = $this->findOrFail($data['cartId']);
        $cart->contents()->delete();
       //dd($data);
        foreach ($data['product'] as $key => $product) {
            //dd($product['type']);
            $sizeExtraCost = 0.00;

            $contentsData = [
                'color' => $product['color'],
                'attr_id' => $product['color_attribute_id'],
                'size' => $product['size']
            ];

            $cart->contents()->create([
                'cart_id' => $cart->id,
                'product_id' => $product['id'],
                'contents' => json_encode($contentsData),
                'positions' => isset($data['positions'][$product['type']]) ? json_encode($data['positions'][$product['type']]) : null
            ]);

            /** calculation for size **/

            if (!empty($product['size'])) {
                foreach ($product['size'] as $size) {
                    if (isset($size['extra_cost'])) {
                        $sizeExtraCost = $sizeExtraCost + ($size['quantity'] * $size['extra_cost']);
                    }
                }
            }

            /** calculation for size **/

            $totalSizeCost = $totalSizeCost + $sizeExtraCost;
        }

        /** calculation for position **/

        $bundle = Bundle::where('id', $data['table_id'])->first();

        if (!empty($data['position_count'])) {
        
            foreach ($bundle->products as $product) {
                $product_count = $data['position_count'][$product->sub_category_id];
                 //dd($product_count);
                if ($product_count > 0) {
                    $cost = ($product_count - 1) * 3;
                    $totalPositionCost += $product->pivot->quantity * $cost;
                }


            /** calculation for adding email **/

            if ((isset($data['is_phone_checked']) && $data['is_phone_checked'] == 'on')) {
                $cart->is_phone_checked = 1;
                $totalInfoDocCost += ($product->pivot->quantity *  2.00);
            }

            /** calculation for adding telephone **/

            if ((isset($data['is_email_checked']) && $data['is_email_checked'] == 'on')) {
                $cart->is_email_checked = 1;
                $totalInfoDocCost += ($product->pivot->quantity *  2.00);
            }
            }
        }


        /** total calculation (sizeCost + positionCost) **/

        $bundlePriceCost = $bundle->price ?? 0.00;
        $totalExtraCost = ($totalSizeCost + $totalPositionCost + $totalInfoDocCost);
        $totalCost = ($bundlePriceCost + $totalExtraCost);

        $cart->size_extra_cost = $totalSizeCost;
        $cart->position_extra_cost = $totalPositionCost;
        $cart->info_extra_cost = $totalInfoDocCost;
        $cart->total_extra_cost = $totalExtraCost;
        $cart->total_cost = $totalCost;
        $cart->save();
        return $cart->sessionId;

    }



    public function submitCart(array $data, array $files)
    {

        $total = 0;
        $carts = Cart::where('sessionId', $data['sessionId'])->get();

        if ($carts->isNotEmpty()) {
            foreach ($carts as $cart) {

                $total += $cart->total_cost;
                $cart->logo = !empty($files) ? json_encode($files) : null;
                $cart->comment = $data['comment'] ?? '';
                $cart->save();
            }
        }

        /** Setting setting for cart processing **/

        $sessionData = [
            'currency_symbol' => Helper::setting('currency-symbol') ?? '$',
            'total' => $total,
            'sessionId' => $data['sessionId'],
            'returnURL' => route('checkout.index')
        ];

        session(['cartData' => $sessionData]);
        return $carts;
    }
}
