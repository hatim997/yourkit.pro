<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view tax');
            $taxes = Tax::all();
            return view('dashboard.tax.index', compact('taxes'));
        } catch (\Throwable $th) {
            Log::error('Tax Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
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
        try {
            $this->authorize('update tax');
            $tax = Tax::findOrFail($id);
            return view('dashboard.tax.edit', compact('tax'));
        } catch (\Throwable $th) {
            Log::error('Tax Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update tax');
        $validator = Validator::make($request->all(), [
            'tax_type' => 'required|string|max:255',
            'slug' => 'required|unique:taxes,slug,'.$id,
            'tax_code' => 'required|string|max:255',
            'percentage' => 'required|integer|min:0',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $tax = Tax::findOrFail($id);
            $tax->tax_type = $request->tax_type;
            $tax->slug = $request->slug;
            $tax->tax_code = $request->tax_code;
            $tax->percentage = $request->percentage;
            $tax->status = $request->status;
            $tax->save();

            DB::commit();
            return redirect()->route('dashboard.taxes.index')->with('success', 'Tax Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Tax Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
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
