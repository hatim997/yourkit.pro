<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view promo code');
            $promoCodes = PromoCode::all();
            return view('dashboard.promo-code.index', compact('promoCodes'));
        } catch (\Throwable $th) {
            Log::error('Promo Codes Index Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $this->authorize('create promo code');
            return view('dashboard.promo-code.create');
        } catch (\Throwable $th) {
            Log::error('Promo Codes Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create promo code');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|unique:promo_codes,code',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'valid_until' => 'required|date',
            'usage_limit' => 'required|integer|min:0',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $promoCode = new PromoCode();
            $promoCode->name = $request->name;
            $promoCode->code = $request->code;
            $promoCode->discount_percentage = $request->discount_percentage;
            $promoCode->valid_from = now();
            $promoCode->valid_until = $request->valid_until;
            $promoCode->usage_limit = $request->usage_limit;
            $promoCode->status = $request->status;
            $promoCode->save();

            DB::commit();
            return redirect()->route('dashboard.promo-codes.index')->with('success', 'Promo Code Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Promo Code Store Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
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
        try {
            $this->authorize('update promo code');
            $promoCode = PromoCode::findOrFail($id);
            return view('dashboard.promo-code.edit', compact('promoCode'));
        } catch (\Throwable $th) {
            Log::error('Promo Code Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update promo code');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|unique:promo_codes,code,'.$id,
            'discount_percentage' => 'required|integer|min:0|max:100',
            'valid_until' => 'required|date',
            'usage_limit' => 'required|integer|min:0',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $promoCode = PromoCode::findOrFail($id);
            $promoCode->name = $request->name;
            $promoCode->code = $request->code;
            $promoCode->discount_percentage = $request->discount_percentage;
            $promoCode->valid_from = now();
            $promoCode->valid_until = $request->valid_until;
            $promoCode->usage_limit = $request->usage_limit;
            $promoCode->status = $request->status;
            $promoCode->save();

            DB::commit();
            return redirect()->route('dashboard.promo-codes.index')->with('success', 'Promo Code Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Promo Code Update Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->authorize('delete promo code');
            $promoCode = PromoCode::findOrFail($id);
            $promoCode->delete();
            return redirect()->route('dashboard.promo-codes.index')->with('success', 'Promo Code Deleted Successfully!');
        } catch (\Throwable $th) {
            Log::error('Promo Code Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
