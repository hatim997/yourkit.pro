<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PageDataTable;
use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class PageController extends Controller
{

    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
       $this->pageRepository =  $pageRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PageDataTable $dataTable)
    {
        return $dataTable->render('admin.page.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $page = $this->pageRepository->getPageContent($id);
        return view('admin.page.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $input = $request->all();

        // return $input;

        try{

            $this->pageRepository->updatePage($id, $input);
            Toastr::success('Page created successfully ','Success');
            return redirect()->route('admin.page.index');

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
        //
    }
}
