<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductBundleDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\ProductRepository;
use App\Repositories\AttributeRepository;
use App\Repositories\BundleRepository;


class ProductBundleController extends Controller
{
    protected $productRepository;
    protected $attributeRepository;
    protected $bundleRepository;


    public function __construct(ProductRepository $productRepository, AttributeRepository $attributeRepository, BundleRepository $bundleRepository)
    {
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->bundleRepository = $bundleRepository;
    }
    //

    /**
     * Display a listing of the resource.
     */
    public function index(ProductBundleDataTable $dataTable)
    {
        return $dataTable->render('admin.productbundle.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = $this->productRepository->getWhere(array('product_type'=>1));
        return view('admin.productbundle.create', compact('products'));
    }

        /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
           

        ], [
            'image.required' => 'Please upload an image.',
           
            
        ]);
        try {
            DB::beginTransaction();
            $this->bundleRepository->createProductBundleWithImage($request);
            DB::commit();


            Toastr::success('Kits Product bundle created successfully ', 'Success');
            return redirect()->route('admin.products-bundle.index');
        } catch (\Exception $e) {

            DB::rollBack();

            return $e->getMessage();

            Toastr::error('Something went wrrong! ', 'Error');
            return back();
        }
    }

     /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $products = $this->productRepository->getWhere(array('product_type'=>1));
        $bundle = $this->bundleRepository->find($id);
        //  return $products ;
         return view('admin.productbundle.edit', compact('products', 'bundle'));
    }

     /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $bundle = $this->bundleRepository->updateProductBundleWithImage($id, $request);


            Toastr::success('Bundle updated successfully ','Success');
            return redirect()->route('admin.products-bundle.index');

        } catch(\Exception $e){

            return $e->getMessage();

            Toastr::error('Something went wrrong! ','Error');
            return back();
        }
    }

}
