<?php

namespace App\Http\Controllers;

use App\DataTables\EcommerceDataTable;
use App\Http\Requests\Cart\SubmitCartRequest;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\CartContent;
use App\Models\EcomAttributeImage;
use App\Models\EcommerceAttribute;
use App\Models\Product;
use App\Models\ProductPosition;
use App\Models\SubCategory;
use App\Repositories\BundleRepository;
use App\Repositories\CartRepository;
use App\Traits\FileUploadTraits;
use App\Utils\Helper;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    use FileUploadTraits;

    protected $cartRepository;
    protected $bundleRepository;

    public function __construct(CartRepository $cartRepository, BundleRepository $bundleRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->bundleRepository = $bundleRepository;
    }

    public function index()
    {
        return view('frontend.cart');
    }

    public function getCartCount(Request $request)
    {

        $count = $this->cartRepository->getCartCount($request->sessionId);
        return $count;
    }

    public function getCart(string $sessionId)
    {

        $carts = $this->cartRepository->getCartDetails($sessionId);

        $html = '';
        $total = 0;
        $quantity = 0;

        if (!$carts->isEmpty()) {

            $html .= ' <div class="cart-prt">
                <div class="cart-header">
                    <h5>Description</h5>
                    <h5>Total</h5>
                </div>';
            foreach ($carts as $cart) {

                $total = $total + $cart->total_cost;
                if ($cart->table == 'bundles') {
                    $html .= '<div>

                    <div class="mid-part py-4 ">
                        <ul class="filter-lst">';

                    if (isset($cart->bundle)) {
                        foreach ($cart->bundle->products as $product) {

                            $image = isset($product->subcategory->media) ? url(asset('storage/' . $product->subcategory->media->path)) : "";

                            $html .= '<li data-aos="zoom-in" data-aos-duration="2000" class="aos-init aos-animate">
                                <div class="filter-ico">
                                    <img src="' . $image . '" alt="">
                                </div>
                                <h4>' . $product->pivot->quantity . ' - ' . $product->name . '</h4>
                            </li>';
                        }
                    }

                    $html .= '
                        </ul>
                        <div class="editdelIcon ">
                                <ul>
                                    <li>
                                        <button onclick="updateCart(' . $cart->id . ')"><i class="bi bi-pencil-square"></i></button>
                                    </li>
                                    <li>
                                        <button onclick="deleteCart(' . $cart->id . ')"><i class="bi bi-trash"></i></button>
                                    </li>
                                </ul>
                        </div>
                    </div>

                    <div class="product-price-prt py-4">
                        <div class="product-lft-prt">';

                    if (isset($cart->contents)) {
                        foreach ($cart->contents as $content) {


                            $attrContents = json_decode($content->contents);
                            $countattrContents = count(json_decode($content->contents, true));

                            // return $attrContents;

                            $html .= '<div>
                            <h4>' . $content->product->name . '</h4>

                            <ul><li><strong>Color:</strong> <span style="background:' . $attrContents->color . '; color:#ccc; padding:10px; display:inline-block; border:1px solid #ccc; width:20px; height:20px;"></li><li><strong>Size & Quantity:</strong>';

                            foreach ($attrContents->size as $key => $size) {

                                if (isset($size->quantity) && $size->quantity > 0) {

                                    $html .= isset($size->attribute_value) ? ' ' . $size->attribute_value . '-' . $size->quantity : ' ' . $size->quantity;
                                }

                                // if ($key < $countattrContents - 1) {
                                //     $html .= ', ';
                                // }
                            }

                            $html .= '</li><li><strong>Printing Locations:</strong>';

                            foreach ($content->position_images as $image) {
                                $html .= '<span class="avater-img"><img src="' . $image . '" alt=""></span>';
                            }


                            $html .= '</ul></div>';
                        }
                    }

                    $html .= '</div>
                        <h5 class="price">' . (Helper::setting('currency-symbol') ?? '$') . ($cart->bundle->price ?? 0) . '</h5>
                    </div>

                </div><div class="print-charge"><h5>Size Extra Cost</h5><h5><strong>' .  (Helper::setting('currency-symbol') ?? '$') . ($cart->size_extra_cost ?? 0) . '</strong></h5></div>
                <div class="print-charge"><h5>Extra Printing Location Cost</h5><h5><strong>' .  (Helper::setting('currency-symbol') ?? '$') . ($cart->position_extra_cost ?? 0) . '</strong></h5></div>
                <div class="print-charge"><h5>Email/Contact Extra Cost</h5><h5><strong>' .  (Helper::setting('currency-symbol') ?? '$') . ($cart->info_extra_cost ?? 0) . '</strong></h5></div>
                <div class="print-charge"><h5></h5><h5><strong>' .  (Helper::setting('currency-symbol') ?? '$') . ($cart->total_cost ?? 0) . '</strong></h5></div>
                <div class="print-charge"></div>';
                } else {

                    ////////////ecom/////////////
                    $html .= '<div>

                <div class="mid-part py-4"><hr>
                    <ul class="filter-lst">';


                    $prod = Product::find($cart->table_id);

                    $image = isset($prod->subcategory->media) ? url(asset('storage/' . $prod->subcategory->media->path)) : "";

                    // $html .= '<li data-aos="zoom-in" data-aos-duration="2000" class="aos-init aos-animate">
                    //         <div class="filter-ico">
                    //             <img src="' . $image . '" alt="">
                    //         </div>

                    //     </li>';



                    $html .= '<li>
                            <button onclick="updateEcomCart(' . $cart->id . ')"><i class="bi bi-pencil-square"></i></button>
                        </li>
                        <li>
                            <button onclick="deleteCart(' . $cart->id . ')"><i class="bi bi-trash"></i></button>
                        </li>
                    </ul>
                </div>

                <div class="product-price-prt py-4">
                    <div class="product-lft-prt">';

                    if (isset($cart->contents)) {
                        foreach ($cart->contents as $content) {


                            $attrContents = json_decode($content->contents);
                            $countattrContents = count(json_decode($content->contents, true));

                            // return $attrContents;

                            $html .= '<div>
                        <h4>' . $prod->name . '</h4>

                        <ul><li><strong>Color:</strong> <span style="background:' . $attrContents->color . '; color:#ccc; padding:10px; display:inline-block; border:1px solid #ccc; width:20px; height:20px;"></li><li><strong>Size:</strong>' . $attrContents->size . '</li><li><strong>Quantity:</strong>' . $attrContents->quantity . '</li>';



                            // if (isset($size->quantity) && $size->quantity > 0) {

                            //     $html .= isset($size->attribute_value) ? ' ' . $size->attribute_value .'-'. $size->quantity : ' ' .$size->quantity;
                            // }

                            // if ($key < $countattrContents - 1) {
                            //     $html .= ', ';
                            // }


                            $html .= '<li><strong>Image:</strong><span class="avater-img"><img src="' . url('storage/' . $attrContents->image) . '" alt=""></span></li>';

                            if ($attrContents->cart_image) {
                                $html .= '<li><strong>Logo Image:</strong></li>';
                                foreach ($attrContents->cart_image as $image) {
                                    $html .= '<li><span class="avater-img"><img src="' . url('storage/' . $image) . '" alt=""></span></li>';
                                }
                            } else {
                                $html .= '<li><strong>Logo Image:</strong> No image available</li>';
                            }
                            if ($attrContents->note) {
                                $html .= '<li><strong>Note:</strong>' . $attrContents->note . '</li>';
                            }
                            // foreach ($content->position_images as $image) {
                            //     $html .= '<span class="avater-img"><img src="' . $image . '" alt=""></span>';
                            // }


                            $html .= '</ul></div>';
                            $html .= '</div>
                    <h5 class="price">' . (Helper::setting('currency-symbol') ?? '$') . ($cart->total_cost ?? 0) . '</h5>
                </div><hr>';
                        }
                    }
                }
            }

            $html .= '<div class="print-charge"><h5>Total :</h5><h5><strong>' .  (Helper::setting('currency-symbol') ?? '$')  . ($total ?? 0) . '</strong></h5></div>
                   
                  
                    </div> </div>';
        } else {
            $html = ' <div class="container-xxl"><div class="cart-prt text-center"><span><strong> Cart is empty!</strong></span><div class="continue-prt py-4 justify-content-center">  
                    <a href="' . route("home") . '" class="btn btn-outline-warning">Continue Shopping</a></div></div>';
        }

        return response()->json(['data' => $html, 'count' => count($carts)]);
    }

    public function submitCart(SubmitCartRequest $request)
    {
        //return 
        //dd($request->all());

        $fileArray = [];
        $input = $request->all();

        try {

            /** Handle Multiple File Upload **/

            if ($request->has('file')) {
                foreach ($request->file('file') as $file) {
                    $upload = $this->uploadFile($file, 'cart');
                    $fileArray[] = $upload['file_path'];
                }
            }

            // return $fileArray;

            $this->cartRepository->submitCart($input, $fileArray);

            if (Auth::check()) {
                return redirect()->route('checkout.index');
            }

            session(['url.intended' => route('checkout.index')]);
            return redirect()->route('signin');
        } catch (\Exception $e) {
        }

        // $input = $request->all();

    }

    public function edit($id)
    {

        // $cart = $this->cartRepository->findOrFail($id);

        $cart = $this->cartRepository->getCartDetailsEdit($id);

        // return $cart;

        $bundle = $this->bundleRepository->getBundleByUUID($cart->bundle->uuid);
        $positions = ProductPosition::whereIn('sub_category_id', json_decode($bundle->subcategories))
            ->with('images')
            ->get();
        $subcategories = SubCategory::whereIn('id', json_decode($bundle->subcategories))->with('productposition', function ($position) {
            $position->with('images');
        })->get();
        // return $positions;
        // return $bundle;
        // return $cart->contents;

        return view('frontend.choose-product-edit', ['bundle' => $bundle, 'meta' => $bundle->uuid, 'positions' => $positions, 'cart' => $cart, 'subcategories' => $subcategories]);
    }

    public function ecomEdit($id)
    {

        $cart = $this->cartRepository->findOrFail($id);
        $contents = json_decode($cart->contents);
        //dd( $attrContents);

        $color = [];
        $size = [];

        $product = Product::find($cart->table_id);
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

        return view('frontend.ecom-edit', ['product' => $product, 'cart' => $cart,]);
    }

    // public function ecomUpdate(Request $request, $id)
    // {


    //     \DB::beginTransaction();

    //     try {

    //         $request->validate([
    //             'color' => 'required',
    //             'size' => 'required',
    //         ], [
    //             'color.required' => 'Please select a color.',
    //             'size.required' => 'Please select a size.',
    //         ]);
    //         $col_attr = AttributeValue::where('value', $request->color)->first();
    //         $siz_attr = AttributeValue::where('value', $request->size)->first();
    //         $cart = $this->cartRepository->findOrFail($id);
    //         $previousCartContent = $cart->contents->first();
    //         $previousAttributes = json_decode($previousCartContent->contents, true);
    //         $prev_note = $previousAttributes['note'] ?? '';
    //         $cart_images = [];
    //         $cart_images = $previousAttributes['cart_image'] ?? [];

    //         $existingCartContent = CartContent::whereHas('cart', function ($query) use ($request) {
    //             $query->where('sessionId', $request->sessionId);
    //         })
    //             ->where('product_id', $request->product_id)
    //             ->whereJsonContains('contents->col_attr_id', $col_attr->id)
    //             ->whereJsonContains('contents->siz_attr_id', $siz_attr->id)
    //             ->first();


    //         foreach ($cart->contents as $content) {
    //             $attr = json_decode($content->contents);
    //         }


    //         if ($request->hasFile('cart_image')) {
    //             foreach ($request->file('cart_image') as $file) {
    //                 if ($file->isValid()) {
    //                     $fileData = $this->uploadFile($file, 'ecom-cart');
    //                     $cart_images[] = $fileData['file_path'];
    //                 }
    //             }
    //         }
    //         if ($existingCartContent) {
    //             // Increment quantity instead of deleting the cart
    //             $contents = json_decode($existingCartContent->contents, true);
    //             $contents['quantity'] += (int)$request->quantity; // Ensure it's an integer

    //             $existingCartContent->update([
    //                 'contents' => json_encode($contents),
    //             ]);

    //             // Update total cost
    //             $cartss = $existingCartContent->cart;
    //             $cartss->total_cost += ((float)$request->price * (int)$request->quantity);
    //             $cartss->save();

    //             \DB::commit();

    //             return response()->json([
    //                 'status' => true,
    //                 'data' => $existingCartContent->cart->sessionId,
    //                 'message' => 'Cart updated successfully (quantity increased).'
    //             ]);
    //         }
    //         $contentsData = [
    //             'quantity' => $request->quantity,
    //             'image' => $request->image,
    //             'color' => $request->color,
    //             'col_attr_id' => $col_attr->id,
    //             'size' => $request->size,
    //             'siz_attr_id' => $siz_attr->id,
    //             'cart_image' => $cart_images,
    //             'note' => $request->note ?? $prev_note,
    //         ];


    //         $cart->contents()->delete();
    //         $cart_contents = CartContent::create([
    //             'cart_id' => $cart->id,
    //             'product_id' => $request->product_id,
    //             'contents' => json_encode($contentsData),

    //         ]);
    //         //dd( $cart_contents);
    //         $totalCost = ((float)$request->price * (int)$request->quantity);

    //         $cart->total_cost = $totalCost;
    //         //$cart->cartId = $cartId;
    //         $cart->save();

    //         \DB::commit();
    //         return response()->json([
    //             'status' => true,
    //             'data' => $cart->sessionId,
    //             'message' => 'Cart inserted successfully'
    //         ]);
    //     } catch (\Exception $e) {

    //         \DB::rollBack();
    //         return response()->json([
    //             'status' => false,
    //             'data' => '',
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }


    public function ecomUpdate(Request $request, $id)
    {
        //return $request->all();
        \DB::beginTransaction();

        try {
            // Validate request
            $request->validate([
                'color' => 'required',
                'size' => 'required',
                'cart_image' => 'nullable|array', 
                'cart_image.*' => 'nullable|mimes:jpg,jpeg,png|max:2048',
                'quantity' => 'required|integer|min:1|max:9999',
                'note' => [
                    function ($attribute, $value, $fail) use ($request) {
                        $hasImages = !empty($request->file('cart_image')); 
                        $hasExistingImages = $request->input('has_existing_images') == "1"; // Check hidden field
                        $hasNote = !empty($value); 
            
                        if (($hasImages || $hasExistingImages) && !$hasNote) {
                            $fail('Note is required when logo images are uploaded.');
                        }
                        if ($hasNote && !$hasImages && !$hasExistingImages) {
                            $fail('At least one logo image is required when a note is provided.');
                        }
                    }
                ],
            ], [
                'color.required' => 'Please select a color.',
                'size.required' => 'Please select a size.',
                'quantity.required' => 'Please enter a quantity.',
                'quantity.integer' => 'Quantity must be a valid number.',
                'quantity.min' => 'Quantity must be at least 1.',
                'quantity.max' => 'Quantity cannot exceed 9999.',
                'cart_image.*.mimes' => 'Each image must be a JPG, JPEG, or PNG file.',
                'cart_image.*.max' => 'Each image cannot exceed 2MB in size.',
            ]);
            
            
            // Fetch attribute values
            $col_attr = AttributeValue::where('value', $request->color)->first();
            $siz_attr = AttributeValue::where('value', $request->size)->first();

            // Find cart
            $cart = $this->cartRepository->findOrFail($id);
            $previousCartContent = $cart->contents->first();
            $previousAttributes = json_decode($previousCartContent->contents, true);
            $prev_note = $previousAttributes['note'] ?? '';
            $cart_images = $previousAttributes['cart_image'] ?? [];

            // Check if the product already exists with new color/size
            $existingCartContent = CartContent::whereHas('cart', function ($query) use ($request) {
                $query->where('sessionId', $request->sessionId);
            })
                ->where('product_id', $request->product_id)
                ->whereJsonContains('contents->col_attr_id', $col_attr->id)
                ->whereJsonContains('contents->siz_attr_id', $siz_attr->id)
                ->first();

            // Handle cart image uploads
            if ($request->hasFile('cart_image')) {
                foreach ($request->file('cart_image') as $file) {
                    if ($file->isValid()) {
                        $fileData = $this->uploadFile($file, 'ecom-cart');
                        $cart_images[] = $fileData['file_path'];
                    }
                }
            }

            if ($existingCartContent) {

                $contents = json_decode($existingCartContent->contents, true);
                if ($existingCartContent->cart_id !== $previousCartContent->cart_id) {
                    $contents['quantity'] += (int)$request->quantity;
                } else {
                    $contents['quantity'] = (int)$request->quantity;
                }

                $contents['cart_image'] = !empty($cart_images) ? $cart_images : ($contents['cart_image'] ?? []);


                $contents['note'] = $request->note ?? ($contents['note'] ?? $prev_note);
                $existingCartContent->update([
                    'contents' => json_encode($contents),
                ]);

                // Update total cost
                $cartss = $existingCartContent->cart;
                $cartss->total_cost += ((float)$request->price * (int)$request->quantity);
                $cartss->save();

                if ($existingCartContent->cart_id !== $previousCartContent->cart_id) {
                    $cart->delete();
                }


                \DB::commit();

                return response()->json([
                    'status' => true,
                    'data' => $existingCartContent->cart->sessionId,
                    'message' => 'Cart updated successfully (quantity merged, old entry removed).'
                ]);
            } else {

                $cart->contents()->delete(); // Delete previous entry

                // Create new cart content
                $contentsData = [
                    'quantity' => $request->quantity,
                    'image' => $request->image,
                    'color' => $request->color,
                    'col_attr_id' => $col_attr->id,
                    'size' => $request->size,
                    'siz_attr_id' => $siz_attr->id,
                    'cart_image' => $cart_images,
                    'note' => $request->note ?? $prev_note,
                ];

                $cart_contents = CartContent::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'contents' => json_encode($contentsData),
                ]);

                // Update cart total cost
                $totalCost = ((float)$request->price * (int)$request->quantity);
                $cart->total_cost = $totalCost;
                $cart->save();

                \DB::commit();

                return response()->json([
                    'status' => true,
                    'data' => $cart->sessionId,
                    'message' => 'Cart updated successfully (color/size changed).'
                ]);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'data' => '',
                'message' => $e->getMessage()
            ]);
        }
    }



    public function delete($id)
    {
        try {
            $this->cartRepository->delete($id);
            return response()->json(['status' => true, 'message' => 'Cart item deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }


    public function submitCartEcom(Request $request)
    {
        //return 
        // return $request->all();
        \DB::beginTransaction();

        try {
            $request->validate([
                'color' => 'required',
                'size' => 'required',
                'cart_image' => 'nullable|array', 
                'cart_image.*' => 'nullable|mimes:jpg,jpeg,png|max:2048',
                'quantity' => 'required|integer|min:1|max:9999',
                'note' => [
                    function ($attribute, $value, $fail) use ($request) {
                        $hasImages = !empty($request->file('cart_image')); 
                        $hasNote = !empty($value); 
                        
                        if ($hasImages && !$hasNote) {
                            $fail('Note is required when logo images are uploaded.');
                        }
                        if ($hasNote && !$hasImages) {
                            $fail('At least one logo image is required when a note is provided.');
                        }
                    }
                ],
            ], [
                'color.required' => 'Please select a color.',
                'size.required' => 'Please select a size.',
                'quantity.required' => 'Please enter a quantity.',
                'quantity.integer' => 'Quantity must be a valid number.',
                'quantity.min' => 'Quantity must be at least 1.',
                'quantity.max' => 'Quantity cannot exceed 9999.',
                'cart_image.*.mimes' => 'Each image must be a JPG, JPEG, or PNG file.',
                'cart_image.*.max' => 'Each image cannot exceed 2MB in size.',
            ]);
            

            $col_attr = AttributeValue::where('value', $request->color)->first();
            $siz_attr = AttributeValue::where('value', $request->size)->first();

            if (!$col_attr || !$siz_attr) {
                return response()->json(['status' => false, 'message' => 'Invalid color or size.']);
            }


            $existingCartContent = CartContent::whereHas('cart', function ($query) use ($request) {
                $query->where('sessionId', $request->sessionId);
            })
                ->where('product_id', $request->product_id)
                ->whereJsonContains('contents->col_attr_id', $col_attr->id)
                ->whereJsonContains('contents->siz_attr_id', $siz_attr->id)
                ->first();
            //dd( $existingCartContent);
            if ($existingCartContent) {
                // Update quantity
                $contents = json_decode($existingCartContent->contents, true);
                $contents['quantity'] += $request->quantity; // Increment quantity

                $existingCartContent->update([
                    'contents' => json_encode($contents),
                ]);
                $cart = $existingCartContent->cart;
                $cart->total_cost += ((float)$request->price * (int)$request->quantity);
                $cart->save();
                \DB::commit();

                return response()->json([
                    'status' => true,
                    'data' => $existingCartContent->cart->sessionId,
                    'message' => 'Cart updated successfully (quantity increased).'
                ]);
            }

            $cartId = generateUUID();

            $cartData = [
                'table' => $request->table,
                'cartId' => $cartId,
                'sessionId' => $request->sessionId,
                'table_id' => $request->table_id,
                // 'comment' => $data['comment']
            ];
            // dd($cartData);
            $cart = Cart::create($cartData);
            //dd( $cart);
            // dd($col_attr);

            $ecomAttr = EcommerceAttribute::where('product_id', $request->table_id)->where('size_value_id', $siz_attr->id)->where('color_value_id', $col_attr->id)->first();
            if ($ecomAttr) {
                $image = EcomAttributeImage::where('ecommerce_attribute_id', $ecomAttr->id)->first();
            }

            $cart_images = [];
            if ($request->hasFile('cart_image')) {
                foreach ($request->file('cart_image') as $file) {
                    if ($file->isValid()) {
                        $fileData = $this->uploadFile($file, 'ecom-cart');
                        $cart_images[] = $fileData['file_path'];
                    }
                }
            }

            $contentsData = [
                'quantity' => $request->quantity,
                'image' => $image->image,
                'color' => $request->color,
                'col_attr_id' => $col_attr->id,
                'size' => $request->size,
                'siz_attr_id' => $siz_attr->id,
                'cart_image' => $cart_images ?? '',
                'note' => $request->note,
            ];

            $cart_contents = CartContent::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'contents' => json_encode($contentsData),
                // 'positions' => isset($data['positions'][$product['type']]) ? json_encode($data['positions'][$product['type']]) : null
            ]);
            //dd( $cart_contents);
            $totalCost = ((float)$request->price * (int)$request->quantity);

            $cart->total_cost = $totalCost;
            $cart->cartId = $cartId;
            $cart->save();
            \DB::commit();
            //dd($cart->sessionId);
            return response()->json([
                'status' => true,
                'data' => $cart->sessionId,
                'message' => 'Cart inserted successfully'
            ]);
        } catch (\Exception $e) {

            \DB::rollBack();
            return response()->json([
                'status' => false,
                'data' => '',
                'message' => $e->getMessage()
            ]);
        }
    }
}
