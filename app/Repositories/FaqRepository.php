<?php
namespace App\Repositories;

use App\Models\Faq;

Class FaqRepository extends BaseRepository {

    public function __construct(Faq $model)
    {
        parent::__construct($model);
    }

    public function getFaqsWithStatus()
    {
        return $this->model->where('status', '1')->get()->sortBy('created_at');
    }

}


?>
