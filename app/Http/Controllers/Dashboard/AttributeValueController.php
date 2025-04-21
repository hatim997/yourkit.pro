<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $this->authorize('create attribute value');
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $attributeValue = new AttributeValue();
            $attributeValue->attribute_id = $request->attribute_id;
            $attributeValue->value = $request->value;
            $attributeValue->status = 1;
            $attributeValue->save();

            DB::commit();
            return redirect()->route('dashboard.attributes.show', $attributeValue->attribute_id)->with('success', 'Attribute Value Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Attribute Value Store Failed', ['error' => $th->getMessage()]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update attribute value');
        $validator = Validator::make($request->all(), [
            'attribute_id' => 'required|exists:attributes,id',
            'value_edit' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $attributeValue = AttributeValue::findOrFail($id);
            $attributeValue->value = $request->value_edit;
            $attributeValue->save();

            DB::commit();
            return redirect()->route('dashboard.attributes.show', $attributeValue->attribute_id)->with('success', 'Attribute Value Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Attribute Value Updated Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('delete attribute value');
            $attributeValue = AttributeValue::findOrFail($id);
            $attributeValue->delete();
            return redirect()->back()->with('success', 'Attribute Value Deleted Successfully!');
        } catch (\Throwable $th) {
            Log::error('Attribute Value Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
