<?php

namespace App\Repositories\Interfaces;

interface AuthenticationInterface
{
    public function loginUser(array $data);
    public function registerUser(array $data, string $type);
}
