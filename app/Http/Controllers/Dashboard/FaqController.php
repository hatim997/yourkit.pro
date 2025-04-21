<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view faq');
            $faqs = Faq::all();
            return view('dashboard.faq.index', compact('faqs'));
        } catch (\Throwable $th) {
            Log::error('FAQ Index Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('create faq');
            return view('dashboard.faq.create');
        } catch (\Throwable $th) {
            Log::error('FAQ Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create faq');
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $faq = new Faq();
            $faq->title = $request->title;
            $faq->description = $request->description;
            $faq->status = $request->status;
            $faq->save();

            DB::commit();
            return redirect()->route('dashboard.faqs.index')->with('success', 'FAQ Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('FAQ Store Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('update faq');
            $faq = Faq::findOrFail($id);
            return view('dashboard.faq.edit', compact('faq'));
        } catch (\Throwable $th) {
            Log::error('FAQ Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update faq');
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $faq = Faq::findOrFail($id);
            $faq->title = $request->title;
            $faq->description = $request->description;
            $faq->status = $request->status;
            $faq->save();

            DB::commit();
            return redirect()->route('dashboard.faqs.index')->with('success', 'FAQ Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('FAQ Update Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('delete faq');
            $faq = Faq::findOrFail($id);
            $faq->delete();
            return redirect()->route('dashboard.faqs.index')->with('success', 'FAQ Deleted Successfully!');
        } catch (\Throwable $th) {
            Log::error('FAQ Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
