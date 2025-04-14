<?php
namespace App\Repositories;
use App\Models\Category;
use App\Models\User;
use App\Repositories\Interfaces\AuthenticationInterface;

Class UserRepository extends BaseRepository implements AuthenticationInterface{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function loginUser(array $data){

    }

    public function registerUser(array $data, string $type){
        $user = $this->create($data);
        $user->assignRole($type);
    }
}