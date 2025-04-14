<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CreateCategoryRequest;
use App\Repositories\CategoryRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository){
        $this->categoryRepository = $categoryRepository;  
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {

        $input = $request->all();

        // dd($input);

        DB::beginTransaction();

        try{
            $this->categoryRepository->createCategory($input);

            DB::commit();

            Toastr::success('Category inserted successfully ','Success');
            return redirect()->route('admin.categories.index');
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
        $category = $this->categoryRepository->findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        DB::beginTransaction();
        try{
            $this->categoryRepository->updateCategory($id, $input);
            DB::commit();
            Toastr::success('Category updated successfully ','Success');
            return redirect()->route('admin.categories.index');

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
        try{

            $this->categoryRepository->delete($id);
            Toastr::success('Category deleted successfully ','Success');
            return redirect()->route('admin.categories.index');

        } catch(\Exception $e){

            return $e->getMessage();
            Toastr::error('Something went wrrong! ','Error');
            return back();

        }
    }
}
