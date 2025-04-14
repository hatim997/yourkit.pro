<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\FileUploadTraits;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use FileUploadTraits;
    public function index(){
        $settings = Setting::get();
        return view('admin.settings.index', compact('settings'));
    }

    public function edit($id){
        $setting = Setting::findOrFail($id);

        $options = null;
        if(!empty($setting->option))
        {
            $options = json_decode($setting->option);
        }

        // return $setting;
        return view('admin.settings.edit', compact('setting', 'options'));

    }

    public function update($id, Request $request){

        $input = $request->all();

        try{
            $setting = Setting::findOrFail($id);
            if($request->hasFile('image')){

                $request->validate([
                    'image' => 'required|max:500|mimes:jpg,jpeg,png',
                ]);
    
                if(!empty($request->image))
                {
        
                    if(is_file(public_path('storage/'.$setting->image)))
                    {
                        unlink(public_path('storage/'.$setting->image));
                    }
                
                    $media = $this->uploadFile($request->file('image'), 'settings');
                    //dd( $media);
                    $setting->update(['value' => $media['file_path']]) ;
                    
                }
               
            }else{
                $request->validate([
                    'value' => 'required',
                ]);
    
                $setting->update($input);
            }

            Toastr::success('Settings updated successfully ','Success');
            return redirect()->route('admin.settings.index');

        } catch(\Exception $e){

            return $e->getMessage();

            Toastr::error('Something went wrrong! ','Error');
            return back();


        }

    }
}
