<?php

namespace App\Repositories;

use App\Models\SubCategory;
use App\Traits\FileUploadTraits;

class SubCategoryRepository extends BaseRepository
{

    use FileUploadTraits;

    public function __construct(SubCategory $model)
    {
        parent::__construct($model);
    }

    public function getSubcategoryWithMedia(){
        return $this->model->with('media')->get();
    }

    public function createSubCategory(array $data)
    {

        $file = $this->uploadFile($data['image'], 'subcategory');
        $subcategory = $this->create($data);

        $subcategory->media()->create([
            'table_name' => 'sub_categories',
            'table_id' => $subcategory->id,
            'path' => $file['file_path'],
            'file_name' => $file['file_name']
        ]);
    }

    public function updateSubCategory($id, array $data)
    {

        $subcategory = $this->update($id, $data);

        if (isset($data['image'])) {

            $file = $this->uploadFile($data['image'], 'subcategory');
            $image = isset($subcategory->media) ? url(asset('storage/'. $subcategory->media->path)) : "";
            $this->deleteFile($image);
            $subcategory->media()->delete();
            $subcategory->media()->create([
                'table_name' => 'sub_categories',
                'table_id' => $subcategory->id,
                'path' => $file['file_path'],
                'file_name' => $file['file_name']
            ]);
        }

        return $subcategory;
    }
}
