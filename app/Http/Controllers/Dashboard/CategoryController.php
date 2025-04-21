<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\FileUploadTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use FileUploadTraits;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view category');
            $categories = Category::with('media')->get();
            return view('dashboard.category.index',compact('categories'));
        } catch (\Throwable $th) {
            Log::error('Category Index Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('create category');
            return view('dashboard.category.create');
        } catch (\Throwable $th) {
            Log::error('Category Add Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create category');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'description' => 'required|string',
            'slug' => 'required|unique:categories,slug',
            'image' => 'nullable|image|max_size',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->description = $request->description;
            $category->status = 1;
            $category->save();

            if ($request->hasFile('image')) {
                $file = $this->uploadFile($request->image, 'categories');
                // dd($file);
                $category->media()->create([
                    'table_name' => 'categories',
                    'table_id' => $category->id,
                    'path' => $file['file_path'] ??'',
                    'file_name' => $file['file_name'] ??''
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.categories.index')->with('success', 'Category Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Category Store Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('update category');
            $category = Category::with('media')->findOrFail($id);
            return view('dashboard.category.edit', compact('category'));
        } catch (\Throwable $th) {
            Log::error('Category Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update category');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20',
            'description' => 'required|string',
            'slug' => 'required|unique:categories,slug,' . $id,
            'image' => 'nullable|image|max_size',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $category = Category::with('media')->findOrFail($id);
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->description = $request->description;
            $category->save();

            if ($request->hasFile('image')) {
                $this->deleteFile($category->media->path);
                $category->media()->delete();
                $file = $this->uploadFile($request['image'], 'categories');
                $category->media()->create([
                    'table_name' => 'categories',
                    'table_id' => $category->id,
                    'path' => $file['file_path'],
                    'file_name' => $file['file_name']
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.categories.index')->with('success', 'Category Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Category Update Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('delete category');
            $category = Category::with('media')->findOrFail($id);
            if ($category->media) {
                $this->deleteFile($category->media->path);
                $category->media()->delete();
            }
            $category->delete();
            return redirect()->back()->with('success', 'Category Deleted Successfully!');
        } catch (\Throwable $th) {
            Log::error('Category Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
