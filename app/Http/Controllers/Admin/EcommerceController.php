<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\EcommerceDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateEcomProductRequest;
use App\Models\EcomAttributeImage;
use App\Models\EcommerceAttribute;
use App\Models\Product;
use App\Repositories\AttributeRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\EcommerceRepository;
use App\Repositories\SubCategoryRepository;
use App\Traits\FileUploadTraits;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EcommerceController extends Controller
{

    protected $ecommerceRepository;
    protected $subCategoryRepository;
    protected $categoryRepository;
    protected $attributeRepository;
    use FileUploadTraits;
    public function __construct(EcommerceRepository $ecommerceRepository, CategoryRepository $categoryRepository ,SubCategoryRepository $subCategoryRepository, AttributeRepository $attributeRepository){
        $this->ecommerceRepository = $ecommerceRepository;
        $this->categoryRepository = $categoryRepository;
        $this->subCategoryRepository = $subCategoryRepository;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(EcommerceDataTable $dataTable)
    {
        return $dataTable->render('admin.ecommerce.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->categoryRepository->get();
        $subcategories = $this->subCategoryRepository->get();

        $sizes = $this->attributeRepository->getSizeAttributes();
        $colors = $this->attributeRepository->getColorAttributes();

        // return $sizes;

        return view('admin.ecommerce.create', compact('categories', 'subcategories', 'sizes', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEcomProductRequest $request)
    {
        $input = $request->all();
         //return $input;

        DB::beginTransaction();
        try{

            $this->ecommerceRepository->storeProduct($input);
            DB::commit();

            Toastr::success('Product created successfully ', 'Success');
            return redirect()->route('admin.ecommerce.index');
        } catch(\Exception $e){

            DB::rollBack();

            return $e->getMessage();

            Toastr::error('Something went wrrong! ', 'Error');
            return back();

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
        $categories = $this->categoryRepository->get();
        $subcategories = $this->subCategoryRepository->get();

        $sizes = $this->attributeRepository->getSizeAttributes();
        $colors = $this->attributeRepository->getColorAttributes();

        $product = Product::with('ecommerce')->find($id);
        return view('admin.ecommerce.edit', compact('categories', 'subcategories', 'sizes', 'colors','product'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $input = $request->all();
    //     $product=Product::with('ecommerce')->find($id);
    //   //dd($input) ;

    //     DB::beginTransaction();
    //     try{
    //         $prod = $product->update($input);
    //        // $existingImages = $product->ecommerce->pluck('image', 'id')->toArray();

    //         foreach ($input['attribute'] as $attribute) {
                
    //             if (!empty($attribute['id'])) {
    //                 $ecom = $product->ecommerce()->find($attribute['id']);
    //                 if ($ecom) {
    //                     $updateData = [
    //                         'size_value_id' => $attribute['size'],
    //                         'color_value_id' => $attribute['color'],
    //                         'price' => $attribute['price'],
    //                         'quantity' => $attribute['quantity'],
    //                     ];
    
                       
    //                     if (!empty($attribute['image'])) {
                            
    //                         $file = $this->uploadFile($attribute['image'], "ecommerce");
    //                         $updateData['image'] = $file['file_path'];
    //                     } 
    
    //                    //dd($updateData);
    //                     $ecom->update($updateData);
    //                 }
    //             } else {
                   
    //                 $insertData = [
    //                     'product_id' => $product->id, 
    //                     'size_id' => 1, 
    //                     'color_id' => 2, 
    //                     'size_value_id' => $attribute['size'],
    //                     'color_value_id' => $attribute['color'],
    //                     'price' => $attribute['price'],
    //                     'quantity' => $attribute['quantity'],
    //                 ];
    
                  
    //                 if (!empty($attribute['image'])) {
    //                     $file = $this->uploadFile($attribute['image'], "ecommerce");
    //                     $insertData['image'] = $file['file_path'];
    //                 }
    
                  
    //                 $product->ecommerce()->create($insertData);
    //             }
    //         }
    
    //         DB::commit();
    
    //         Toastr::success('Product updated successfully', 'Success');
    //         return redirect()->route('admin.ecommerce.index');
          
    //     } catch(\Exception $e){

    //         DB::rollBack();

    //         return $e->getMessage();

    //         Toastr::error('Something went wrrong! ', 'Error');
    //         return back();

    //     }
    // }

  public function update(Request $request, string $id)
{
    $input = $request->all();
    $product = Product::with('ecommerce')->find($id);

    DB::beginTransaction();
    try {
        // Handle size chart upload
        if ($request->hasFile('size_chart') && $request->file('size_chart')->isValid()) {
            $sizeChartFile = $this->uploadFile($request->file('size_chart'), "size_charts");

            if (!empty($product->size_chart) && \Storage::disk('public')->exists($product->size_chart)) {
                \Storage::disk('public')->delete($product->size_chart);
            }

            $input['size_chart'] = $sizeChartFile['file_path'];
        }

       
        $product->update($input);

       
        if (!empty($input['deleted_attributes'])) {
            EcommerceAttribute::whereIn('id', $input['deleted_attributes'])->delete();
        }

        foreach ($input['attribute'] as $attribute) {
            if (!empty($attribute['id'])) {
               
                $ecom = $product->ecommerce()->find($attribute['id']);
                if ($ecom) {
                    $updateData = [
                        'size_value_id' => $attribute['size'],
                        'color_value_id' => $attribute['color'],
                        'price' => $attribute['price'],
                        'quantity' => $attribute['quantity'],
                    ];
                    
                    $ecom->update($updateData);

                    
                    if (!empty($attribute['image']) && is_array($attribute['image'])) {
                        foreach ($attribute['image'] as $imageFile) {
                            if ($imageFile->isValid()) {
                                $file = $this->uploadFile($imageFile, "ecommerce");
                                EcomAttributeImage::create([
                                    'ecommerce_attribute_id' => $ecom->id,
                                    'image' => $file['file_path'],
                                ]);
                            }
                        }
                    }
                }
            } else {
               
                $insertData = [
                    'product_id' => $product->id,
                    'size_id' => 1, 
                    'color_id' => 2,
                    'size_value_id' => $attribute['size'],
                    'color_value_id' => $attribute['color'],
                    'price' => $attribute['price'],
                    'quantity' => $attribute['quantity'],
                ];

                $ecom = $product->ecommerce()->create($insertData);

               
                if (!empty($attribute['image']) && is_array($attribute['image'])) {
                    foreach ($attribute['image'] as $imageFile) {
                        if ($imageFile->isValid()) {
                            $file = $this->uploadFile($imageFile, "ecommerce");
                            EcomAttributeImage::create([
                                'ecommerce_attribute_id' => $ecom->id,
                                'image' => $file['file_path'],
                            ]);
                        }
                    }
                }
            }
        }

        DB::commit();

        Toastr::success('Product updated successfully', 'Success');
        return redirect()->route('admin.ecommerce.index');
        
    } catch (\Exception $e) {
        DB::rollBack();
        Toastr::error('Something went wrong! ' . $e->getMessage(), 'Error');
        return back();
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
