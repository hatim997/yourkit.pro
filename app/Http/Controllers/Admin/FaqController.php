<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\FaqDataTable;
use App\Http\Controllers\Controller;
use App\Repositories\FaqRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    protected $faqReporitory;

    public function __construct(FaqRepository $faqReporitory)
    {
        $this->faqReporitory = $faqReporitory;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FaqDataTable $dataTable)
    {
        return $dataTable->render('admin.faq.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $input = $request->all();

        try{
            $this->faqReporitory->create($input);

            Toastr::success('Faq created successfully ','Success');
            return redirect()->route('admin.faq.index');
        } catch(\Exception $e){

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
        $faq = $this->faqReporitory->findOrFail($id);
        return view('admin.faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        try{
            $this->faqReporitory->update($id, $input);
            Toastr::success('Faq updated successfully ','Success');
            return redirect()->route('admin.faq.index');

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
            $this->faqReporitory->delete($id);
            Toastr::success('Faq deleted successfully ','Success');
            return redirect()->route('admin.faq.index');

        } catch(\Exception $e){
            return $e->getMessage();

            Toastr::error('Something went wrrong! ','Error');
            return back();
        }
    }
}
