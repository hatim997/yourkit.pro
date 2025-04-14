<?php
namespace App\Repositories;

use App\Models\Faq;

Class FaqRepository extends BaseRepository {

    public function __construct(Faq $model)
    {
        parent::__construct($model);
    }

}


?>