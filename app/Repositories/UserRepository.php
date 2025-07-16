<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function all($params = [])
    {
        return User::filter($params)->paginate(10);
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }
}
