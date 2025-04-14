<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubcategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subcategory\CreateSubCategoryRequest;
use App\Http\Requests\Admin\Subcategory\UpdateSubCategoryRequest;
use App\Models\SubCategory;
use App\Repositories\CategoryRepository;
use App\Repositories\SubCategoryRepository;
use App\Traits\FileUploadTraits;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Termwind\render;

class SubCategoryController extends Controller
{

    protected $subCategoryRepository;
    protected $categoryRepository;

    public function __construct(SubCategoryRepository $subCategoryRepository, CategoryRepository $categoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
        $this->categoryRepository = $categoryRepository;
    }



    /**
     * Display a listing of the resource.
     */
    public function index(SubcategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.subcategory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->categoryRepository->get();
        return view('admin.subcategory.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSubCategoryRequest $request)
    {

        $input = $request->all();
        DB::beginTransaction();

       try{

        $this->subCategoryRepository->createSubCategory($input);

        DB::commit();

        Toastr::success('Sub category created successfully ','Success');
        return redirect()->route('admin.sub-categories.index');

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
        $subcategory= $this->subCategoryRepository->findOrFail($id);
        $categories = $this->categoryRepository->get();
        return view('admin.subcategory.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubCategoryRequest $request, string $id)
    {

        $input = $request->all();

        try{
            $this->subCategoryRepository->updateSubCategory($id, $input);
            Toastr::success('Sub Category updated successfully ','Success');
            return redirect()->route('admin.sub-categories.index');

        } catch(\Exception $e){

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
        try{

            $this->subCategoryRepository->delete($id);
            Toastr::error('Sub Category deleted successfully ','Success');
            return redirect()->route('admin.sub-categories.index');

        } catch(\Exception $e){

            return $e->getMessage();
            Toastr::error('Something went wrrong! ','Error');
            return back();

        }
    }
}
