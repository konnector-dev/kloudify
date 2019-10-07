<?php

namespace App\Repositories;

use App\User;

class UserRepository
{
    public function create($userData)
    {
        print_r($userData);exit;
        $user   = new User();
        $user->name = $userData->name;
        $user->email = $userData->email;
        $user->password = bcrypt($userData->password);
        $user->save();

        return $user;
    }
}