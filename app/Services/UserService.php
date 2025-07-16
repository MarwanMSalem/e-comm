<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;

class UserService
{
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll($params)
    {
        return $this->repo->all($params);
    }

    public function getById($id)
    {
        return $this->repo->find($id);
    }

    public function update(User $user, array $data)
    {
        return $this->repo->update($user, $data);
    }
}
