<?php

namespace App\Repositories;

use App\Models\EmailTemplate;
use App\Repositories\BaseRepository;

class EmailTemplateRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'subject',
        'content',
        'email_type',
        //'status',
    ];

    public function __construct(EmailTemplate $model)
    {
        parent::__construct($model);
    }

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return EmailTemplate::class;
    }
}