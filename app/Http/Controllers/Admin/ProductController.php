<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\SubCategoryRepository;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

class ProductController extends Controller
{

    protected $productRepository;
    protected $subCategoryRepository;
    protected $categoryRepository;
    protected $attributeRepository;


    public function __construct(ProductRepository $productRepository, SubCategoryRepository $subCategoryRepository, CategoryRepository $categoryRepository, AttributeRepository $attributeRepository)
    {
        $this->productRepository = $productRepository;
        $this->subCategoryRepository = $subCategoryRepository;
        $this->categoryRepository = $categoryRepository;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
     
        $html = '';
        $counter = 0;

        $categories = $this->categoryRepository->get();
        $subcategories = $this->subCategoryRepository->get();
        $attributes = $this->attributeRepository->getAllAtrributeWithValue();

        // return $attributesValue;

        // foreach ($attributes as $key => $attribute) {
        //     $multiple='';
        //     if($attribute->id == 1){
        //         $multiple = 'multiple';
        //     }
        //     $html .= '<div class="col-12 col-lg-12">
        //     <label for="' . strtolower($attribute->type) . '_' . $key . '" class="col-form-label">' . $attribute->type . '<span class="text-danger">*</span></label>
        //     <input type="hidden" name="attribute_name[]" value="'.strtolower($attribute->type).'">
        //     <input type="hidden" name="attribute_id[]" value="'.$attribute->id.'">
        //     <table class="table table-stripped" id="variantTable_' . strtolower($attribute->type) . '">
        //         <tbody>
        //             <tr id="row-'.strtolower($attribute->type).'_'. $counter .'">
        //                 <td>
        //                     <select class="form-select '. $multiple .'" '.$multiple.' name="' . strtolower($attribute->type) . '[]" id="' . strtolower($attribute->type) . '' . $counter . '" required="">
        //                         <option value="">Select Option</option>';

        //                         foreach($attribute->attributeValues as $key2 => $value){
        //                             $html .='<option value="'. $value->id .'">'. $value->value .'</option>';
        //                         }

        //                     $html .='</select>
        //                 </td>';

        //                 if($attribute->id == 2){
        //                     $html .='<td><input class="form-control" type="file" name="' . strtolower($attribute->type) . '[' . $counter . '][image]"></td><td><button type="button" data-attribute="' . $attribute->id . '" data-table="'. strtolower($attribute->type) .'" class="btn btn-success addRow"><i class="fa fa-plus"></i></button></td>';
        //                 } else {
        //                     $html .='<td></td>';
        //                 }
        //                 $html .='
        //             </tr>
        //         </tbody>
        //     </table>

        // </div>';
        // }
        
        foreach ($attributes as $key => $attribute) {
           //dd($attribute);
            $multiple='';
            $required = 'required';
            if($attribute->id == 1){
                $multiple = 'multiple';
                $required = '';
            }
            $html .= '<div class="col-12 col-lg-12">
            <label for="' . strtolower($attribute->type) . '_' . $key . '" class="col-form-label">' . $attribute->type . '<span class="text-danger">*</span>';
            if ($attribute->id == 1) {
                $html .= ' <small class="text-muted">(For cap and beanie, size is not required)</small>';
            }

if ($attribute->id == 2) {
    $html .= ' <small class="text-muted">(Image resolution must be 350px × 300px)</small>';
}

$html .= '</label>
            <input type="hidden" name="attribute_name[]" value="'.strtolower($attribute->type).'">
            <input type="hidden" name="attribute_id[]" value="'.$attribute->id.'">

            <table class="table table-stripped" id="variantTable_' . strtolower($attribute->type) . '">
                <tbody>
                    <tr id="row-'.strtolower($attribute->type).'_'. $counter .'">
                        <td>
                            <select class="form-select '. $multiple .'" '.$multiple.' name="' . strtolower($attribute->type) . '[][value]" id="' . strtolower($attribute->type) . '' . $counter . '" '.$required.'>
                                <option value="">Select Option</option>';

                                foreach($attribute->attributeValues as $key2 => $value){
                                    if($attribute->id == 2 ){
                                        $html .='<option value="'. $value->id .'" style="background-color: '.$value->value.';">'. $value->value .'</option>';
                                    }else{
                                        $html .='<option value="'. $value->id .'">'. $value->value .'</option>';
                                    }

                                }

                            $html .='</select>
                        </td>';

                        if($attribute->id == 2){
                            $html .='<td><input class="form-control" type="file" name="' . strtolower($attribute->type) . '[' . $counter . '][image]"></td><td><button type="button" data-attribute="' . $attribute->id . '" data-table="'. strtolower($attribute->type) .'" class="btn btn-success addRow"><i class="fa fa-plus"></i></button></td>';
                        } else {
                            $html .='<td></td>';
                        }
                        $html .='
                    </tr>
                </tbody>
            </table>

        </div>';
        }

        return view('admin.products.create', compact('categories', 'subcategories', 'attributes', 'html'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       //   return $request->all();

        $input = $request->all();



        try {
            DB::beginTransaction();
            $product = $this->productRepository->createProductWithAttributes($input);

            DB::commit();


            Toastr::success('Product created successfully ', 'Success');
            return redirect()->route('admin.products.index');
        } catch (\Exception $e) {

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
        $html = '';
        $counter = 0;

        $categories = $this->categoryRepository->get();
        $subcategories = $this->subCategoryRepository->get();
        $attributes = $this->attributeRepository->get();

        $product = $this->productRepository->findOrFail($id);
        // $pp = ProductAttribute::find(50);

        $attrValues =  collect($product?->attributes?->pluck('pivot')?->toArray()) ;
        $productAttribute='';

      // return $attrValues;
     // return  \Helper::keyValueExists($attrValues, 'value' , 2) ;


        foreach ($attributes as $key => $attribute) {
           // return $attribute->attributeValues ;
            $html .= '<input type="hidden" name="attribute_name[]" value="'.strtolower($attribute->type).'">
                <input type="hidden" name="attribute_id[]" value="'.$attribute->id.'">' ;

            $multiple='';
            if($attribute->id == 1){
                $multiple = 'multiple';
                $html .= '<div class="col-12 col-lg-12">
                <label for="' . strtolower($attribute->type) . '_' . $key . '" class="col-form-label">' . $attribute->type . '<span class="text-danger">*</span></label>';
                if ($attribute->id == 1) {
                    $html .= ' <small class="text-muted">(For cap and beanie, size is not required)</small>';
                }
    
    
               $html.= '<table class="table table-stripped" id="variantTable_' . strtolower($attribute->type) . '">
                    <tbody>
                        <tr id="row-'.strtolower($attribute->type).'_'. $counter .'">
                            <td>
                                <select class="form-select '. $multiple .'" '.$multiple.' name="' . strtolower($attribute->type) . '[][value]" id="' . strtolower($attribute->type) . '' . $counter . '" >
                                    <option value="">Select Option</option>';

                                    foreach($attribute->attributeValues as $key2 => $value){
                                    //   return  $value->id ;
                                         if(\Helper::keyValueExists($attrValues, 'value', $value->id)){

                                                $html .='<option value="'. $value->id .'" selected>'. $value->value .'</option>';
                                          }else{
                                                $html .='<option value="'. $value->id .'">'. $value->value .'</option>';
                                          }




                                    }

                                $html .='</select>
                            </td>';
                            $html .='<td></td>';


                            $html .='
                        </tr>
                    </tbody>
                </table>

            </div>';
            }
            if($attribute->id == 2){

                $multiple = '';
                $html .= '<div class="col-12 col-lg-12">
                <label for="' . strtolower($attribute->type) . '_' . $key . '" class="col-form-label">' . $attribute->type . '<span class="text-danger">*</span></label> <small class="text-muted">(Image resolution must be 350px × 300px)</small>


                <table class="table table-stripped" id="variantTable_' . strtolower($attribute->type) . '">
                    <tbody>';

                        $allValues = array_values($attrValues->where('attribute_id', $attribute->id)->toArray()) ;


                        foreach($allValues as $keyVal => $pVal){


                            $html .=  '<tr id="row-'.strtolower($attribute->type).'_'. $keyVal .'">';
                                 $html .= '<td>
                                            <select class="form-select '. $multiple .'" '.$multiple.' name="' . strtolower($attribute->type) . '[][value]" id="' . strtolower($attribute->type) . $counter . '" required="">
                                                <option value="">Select Option</option>';
                                                foreach($attribute->attributeValues as $key2 => $value){
                                                    if($pVal['value'] == $value->id){
                                                        $productAttribute = ProductAttribute::find($pVal['id']);
                                                        $html .='<option value="'. $value->id .'" selected style="background-color: '.$value->value.';">'. $value->value .'</option>';
                                                    }else{
                                                        $html .='<option value="'. $value->id .'" style="background-color: '.$value->value.';">'. $value->value .'</option>';
                                                    }

                                                }
                                    $html .='</select>
                                           </td>';
                                    $html .= '<td><input class="form-control" type="file" name="' . strtolower($attribute->type) . '[' . $keyVal . '][image]"></td>';
                                    if($keyVal == 0){
                                        $html .=  '<td><button type="button" data-attribute="' . $attribute->id . '" name="" data-table="'. strtolower($attribute->type) .'" class="btn btn-success addRow"><i class="fa fa-plus"></i></button></td>';
                                    }else{
                                        $html .=  '<td><button type="button" data-attribute="' . $attribute->id . '" data-table="'. strtolower($attribute->type) .'" class="btn btn-danger" onclick="removeRow('.($keyVal).')"><i class="fa fa-minus"></i></button></td>';
                                    }


                                    if(is_file(public_path('storage/'.$productAttribute?->image))){
                                        $html .='<td><input type="hidden" value="'.$productAttribute?->image.'" name="color['.$keyVal.'][old_image]"><img src="'.url(asset('storage/'.$productAttribute?->image)).'" width="50" height="50"></td>';
                                    }else{
                                        $html .='<td></td>' ;
                                    }
                                    $html .='</tr>';


                    $counter++ ;


                    }
                    $html .=' </tbody>
                                     </table>
                        </div>';
           }

        }

        //echo $html;die;
        return view('admin.products.edit', compact('categories', 'subcategories', 'attributes', 'product', 'html'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {


        $input = $request->all();
       // return $input;


        // try {

            DB::beginTransaction();

            $data = $this->productRepository->updateProductWithAttributes($input, $id);

            DB::commit();

            Toastr::success('Product updated successfully ', 'Success');

            return redirect()->route('admin.products.index');

        // } catch (\Exception $e) {

        //     DB::rollBack();

        //     return $e->getMessage();

        //     Toastr::error('Something went wrrong! ', 'Error');
        //     return back();
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $this->productRepository->deleteProductWithAttribute($id);

            DB::commit();
            Toastr::success('Product deleted successfully ', 'Success');
            return redirect()->route('admin.products.index');
        } catch (\Exception $e) {

            return $e->getMessage();
            Toastr::error('Something went wrrong! ', 'Error');
            return back();
        }
    }
}
