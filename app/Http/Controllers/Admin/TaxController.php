<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\TaxDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tax\UpdateTaxRequest;
use App\Models\Tax;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaxDataTable $dataTable)
    {
        return $dataTable->render('admin.tax.index');
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
        $tax = Tax::findOrFail($id);
        return view('admin.tax.edit', compact('tax'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaxRequest $request, string $id)
    {
        try {
            $tax = Tax::findOrFail($id);
            $tax->tax_type = $request->tax_type;
            $tax->tax_code = $request->tax_code;
            $tax->percentage = $request->percentage;

            $tax->save();

            Toastr::success('Tax updated successfully', 'Success');
            return redirect()->route('admin.tax.index');
        } catch (\Exception $e) {

            return $e->getMessage();

            Toastr::error('Something went wrrong! ', 'Error');
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
