<?php

namespace App\Http\Controllers;

use App\Services\AdminUsersService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Models\User;


class AdminUsersController extends Controller
{
    protected $adminUsersService;
    public function __construct(AdminUsersService $adminUsersService)
    {
        $this->adminUsersService = $adminUsersService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->adminUsersService->getUsersDataTable();
        }
        return view('admin.tables');
    }

    public function create()
    {
        return view('admin.createUser');
    }

    public function store(User $user, RegisterRequest $request)
    {
        $this->adminUsersService->createUser($user, $request->validated());

        return redirect()->back()->with('profile_success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        return response()->json($user);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $role = $request->input('role');
        return $this->adminUsersService->updateUser($user,$request->validated(), $role);
        
    }

    public function destroy(User $user)
    {
        $this->adminUsersService->deleteUser($user);
        
        return response()->json(['message' => 'User deleted successfully']);
    }
}
