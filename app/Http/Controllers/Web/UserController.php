<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $users = $this->service->getAll($request->query());
        return response()->json($users);
    }

    public function show($id)
    {
        $user = $this->service->getById($id);
        return response()->json($user);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $user = $this->service->update($user, $data);
        return response()->json($user);
    }
}
