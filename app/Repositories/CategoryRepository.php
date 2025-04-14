<?php
namespace App\Repositories;
use App\Models\Category;
use App\Traits\FileUploadTraits;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;

Class CategoryRepository extends BaseRepository {

    use FileUploadTraits;

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function createCategory(array $data){

    $file = (isset($data['image']) && !empty($data['image'])) ? $this->uploadFile($data['image'], 'categories') : null;

        $category = $this->create($data);
        $category->media()->create([
            'table_name' => 'categories',
            'table_id' => $category->id,
            'path' => $file['file_path'] ??'',
            'file_name' => $file['file_name'] ??''
        ]);

        return $category;
    }

    public function updateCategory($id, array $data){

        $category = $this->update($id, $data);

        if(isset($data['image'])){

            $file = $this->uploadFile($data['image'], 'categories');
            $this->deleteFile($category->media->path);
            $category->media()->delete();
            $category->media()->create([
                'table_name' => 'categories',
                'table_id' => $category->id,
                'path' => $file['file_path'],
                'file_name' => $file['file_name']
            ]);

        }

        return $category;
    }

}


?>