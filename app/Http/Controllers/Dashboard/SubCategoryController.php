<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\FileUploadTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    use FileUploadTraits;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view sub category');
            $subCategories = SubCategory::with('category')->get();
            return view('dashboard.sub-category.index',compact('subCategories'));
        } catch (\Throwable $th) {
            Log::error('Sub Category Index Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('create sub category');
            $categories = Category::where('status', 1)->get();
            return view('dashboard.sub-category.create', compact('categories'));
        } catch (\Throwable $th) {
            Log::error('Sub Category Add Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create sub category');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'description' => 'required|string',
            'slug' => 'required|unique:sub_categories,slug',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max_size',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $subcategory = new SubCategory();
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->description = $request->description;
            $subcategory->category_id = $request->category_id;
            $subcategory->status = 1;
            $subcategory->save();

            if ($request->hasFile('image')) {
                $file = $this->uploadFile($request->image, 'subcategory');
                // dd($file);
                $subcategory->media()->create([
                    'table_name' => 'sub_categories',
                    'table_id' => $subcategory->id,
                    'path' => $file['file_path'],
                    'file_name' => $file['file_name']
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.sub-categories.index')->with('success', 'Sub Category Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Sub Category Store Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('update sub category');
            $subCategory = SubCategory::with('media')->findOrFail($id);
            $categories = Category::where('status', 1)->get();
            return view('dashboard.sub-category.edit', compact('subCategory','categories'));
        } catch (\Throwable $th) {
            Log::error('Sub Category Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update sub category');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'slug' => 'required|unique:sub_categories,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max_size',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $subcategory = SubCategory::with('media')->findOrFail($id);
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->description = $request->description;
            $subcategory->category_id = $request->category_id;
            $subcategory->save();

            if ($request->hasFile('image')) {
                $this->deleteFile($subcategory->media->path);
                $subcategory->media()->delete();
                $file = $this->uploadFile($request->image, 'subcategory');
                $subcategory->media()->create([
                    'table_name' => 'sub_categories',
                    'table_id' => $subcategory->id,
                    'path' => $file['file_path'],
                    'file_name' => $file['file_name']
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.sub-categories.index')->with('success', 'Sub Category Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Sub Category Update Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('delete sub category');
            $subcategory = SubCategory::with('media')->findOrFail($id);
            if ($subcategory->media) {
                $this->deleteFile($subcategory->media->path);
                $subcategory->media()->delete();
            }
            $subcategory->delete();
            return redirect()->back()->with('success', 'Sub Category Deleted Successfully!');
        } catch (\Throwable $th) {
            Log::error('Sub Category Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
