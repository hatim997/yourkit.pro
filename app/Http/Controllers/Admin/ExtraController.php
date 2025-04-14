<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use App\Repositories\AttributeRepository;
use Illuminate\Http\Request;

class ExtraController extends Controller
{

    // public function getAttributesValue(Request $request, AttributeRepository $attributeRepository)
    // {

    //     // return $request->values;
    //     $value = $attributeRepository->getAttributeValuesWhereIn($request->values);
    //     // return $value;

    //     $html = '';
    //     $headArray = [];
    //     $bodyArray = [];
    //     $heads = '' ;

    //     if (!empty($value)) {
    //         foreach($value as $key => $val){
    //             $headArray[] = $val->type;
    //             $bodyArray[] = $val->arrtibutesvalue;
    //         }

    //     }



    //     return response()->json([
    //         'heads' => $headArray,
    //         'data' => $bodyArray
    //     ]);
    // }

    public function getAttributesValue(Request $request, AttributeRepository $attributeRepository){

        $attribute = $attributeRepository->getAttributeValuesWhereIn($request->attribute);
        return response()->json(['status' => true, 'data' => $attribute]);

    }
}
