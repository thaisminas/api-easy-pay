<?php

namespace App\Domain\Repository;


use App\Infra\Repository\Doctrine\UserEntity;

interface UserRepository
{
    public function save(UserEntity $user): void;
}