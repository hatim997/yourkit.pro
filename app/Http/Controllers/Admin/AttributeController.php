<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AttributeDataTable;
use App\Http\Controllers\Controller;
use App\Repositories\AttributeRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{

    protected $attributeRepository;

    public function __construct(AttributeRepository $attributeRepository){

        $this->attributeRepository = $attributeRepository;
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index(AttributeDataTable $dataTable)
    {
        return $dataTable->render('admin.attributes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        DB::beginTransaction();

        try{
            $this->attributeRepository->createAttributes($input);
            DB::commit();
            Toastr::success('Attribute inserted successfully ','Success');
            return redirect()->route('admin.attributes.index');
        } catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();

            Toastr::error('Something went wrrong! ','Error');
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
        $attribute = $this->attributeRepository->getAllAttributesValue($id);
        return view('admin.attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        DB::beginTransaction();

        try{
            $this->attributeRepository->updateAttributes($id, $input);
            DB::commit();
            Toastr::success('Attribute updated successfully ','Success');
            return redirect()->route('admin.attributes.index');
        } catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();

            Toastr::error('Something went wrrong! ','Error');
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
