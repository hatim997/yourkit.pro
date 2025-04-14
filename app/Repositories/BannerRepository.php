<?php
namespace App\Repositories;

use App\Models\Banner;
use App\Traits\FileUploadTraits;

Class BannerRepository extends BaseRepository {

    use FileUploadTraits;

    public function __construct(Banner $model)
    {
        parent::__construct($model);
    }

    public function createBanner(array $data){

        $file = $this->uploadFile($data['image'], 'banners');
        $data['type']='top';
        $banner = $this->create($data);
        $banner->media()->create([
            'table_name' => 'banners',
            'table_id' => $banner->id,
            'path' => $file['file_path'],
            'file_name' => $file['file_name']
        ]);

        return $banner;

    }

    public function updateBanner($id, array $data){

        $banner = $this->update($id, $data);

        if(isset($data['image'])){

            $file = $this->uploadFile($data['image'], 'banners');
            $this->deleteFile($banner->media->path);
            $banner->media()->delete();
            $banner->media()->create([
                'table_name' => 'banners',
                'table_id' => $banner->id,
                'path' => $file['file_path'],
                'file_name' => $file['file_name']
            ]);

        }



        return $banner;

    }

}


?>