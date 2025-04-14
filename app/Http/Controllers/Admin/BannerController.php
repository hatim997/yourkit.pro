<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\BannerDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\CreateBannerRequest;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;
use App\Repositories\BannerRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{

    protected $bannerRepository;

    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(BannerDataTable $dataTable)
    {
        return $dataTable->render('admin.banner.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBannerRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $this->bannerRepository->createBanner($input);

            DB::commit();

            Toastr::success('Banner created successfully ', 'Success');
            return redirect()->route('admin.banners.index');
        } catch (\Exception $e) {

            DB::rollBack();

            return $e->getMessage();

            Toastr::error('Something went wrrong! ', 'Error');
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
        $banner = $this->bannerRepository->findOrFail($id);
        return view('admin.banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, string $id)
    {
        $input = $request->all();

        DB::beginTransaction();
        try{
            $this->bannerRepository->updateBanner($id, $input);
            DB::commit();
            Toastr::success('Banner updated successfully ','Success');
            return redirect()->route('admin.banners.index');

        } catch(\Exception $e){

            DB::rollBack();

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

            $this->bannerRepository->delete($id);
            Toastr::success('Banner deleted successfully ','Success');
            return redirect()->route('admin.banners.index');

        } catch(\Exception $e){

            return $e->getMessage();
            Toastr::error('Something went wrrong! ','Error');
            return back();

        }
    }
}
