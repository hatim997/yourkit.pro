<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Traits\FileUploadTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    use FileUploadTraits;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view banner');
            $banners = Banner::with('media')->get();
            return view('dashboard.banner.index', compact('banners'));
        } catch (\Throwable $th) {
            Log::error('Banner Index Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('create banner');
            return view('dashboard.banner.create');
        } catch (\Throwable $th) {
            Log::error('Banner Create Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create banner');
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:top,middle,bottom',
            'url_name' => 'required|string|max:255',
            'url' => 'required|url',
            'description' => 'required',
            'image' => 'required|file|max_size',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $banner = new Banner();
            $banner->title = $request->title;
            $banner->type = $request->type;
            $banner->url_name = $request->url_name;
            $banner->url = $request->url;
            $banner->description = $request->description;
            $banner->status = $request->status;
            $banner->save();
            if ($request->hasFile('image')) {
                $file = $this->uploadFile($request['image'], "banners");
                $banner->media()->create([
                    'table_name' => 'banners',
                    'table_id' => $banner->id,
                    'path' => $file['file_path'],
                    'file_name' => $file['file_name']
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.banners.index')->with('success', 'Banner Added Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Banner Store Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('update banner');
            $banner = Banner::with('media')->findOrFail($id);
            return view('dashboard.banner.edit', compact('banner'));
        } catch (\Throwable $th) {
            Log::error('Banner Edit Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update banner');
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:top,middle,bottom',
            'url_name' => 'required|string|max:255',
            'url' => 'required|url',
            'description' => 'required',
            'image' => 'nullable|file|max_size',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();
            $banner = Banner::findOrFail($id);
            $banner->title = $request->title;
            $banner->type = $request->type;
            $banner->url_name = $request->url_name;
            $banner->url = $request->url;
            $banner->description = $request->description;
            $banner->status = $request->status;
            $banner->save();
            if ($request->hasFile('image')) {
                if ($banner->media) {
                    $this->deleteFile($banner->media->path);
                    $banner->media->delete();
                }
                $file = $this->uploadFile($request['image'], "banners");
                $banner->media()->create([
                    'table_name' => 'banners',
                    'table_id' => $banner->id,
                    'path' => $file['file_path'],
                    'file_name' => $file['file_name']
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.banners.index')->with('success', 'Banner Updated Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Banner Update Failed', ['error' => $th->getMessage()]);
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
            $this->authorize('delete banner');
            $banner = Banner::findOrFail($id);
            if ($banner->media) {
                $this->deleteFile($banner->media->path);
                $banner->media->delete();
            }
            $banner->delete();
            return redirect()->route('dashboard.banners.index')->with('success', 'Banner Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Banner Delete Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
