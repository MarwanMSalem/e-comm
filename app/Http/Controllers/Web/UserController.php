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

    public function adminIndex(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $params = $request->only(['search', 'role', 'email']);
        $users = $this->service->getAll($params);
        return view('user.index', compact('users', 'params'));
    }

    public function adminUpdate(Request $request, User $user)
    {
        $authUser = auth()->user();
        if ($authUser->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'role' => 'required|in:admin,user,employee',
        ]);
        $user->role = $request->input('role');
        $user->save();
        return redirect()->route('users.index')->with('success', 'User role updated.');
    }

    public function destroy(User $user)
    {
        $authUser = auth()->user();
        if ($authUser->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted.');
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        if ($authUser->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|in:admin,user,employee',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        $this->service->create($validated);
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
}
