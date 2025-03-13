<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AdminUsersService
{

    public function getUsersDataTable()
    {
        $users = User::byRole('user')->get();
        return DataTables::of($users)
            ->addIndexColumn()
            ->make(true);
    }

    public function createUser(User $user, array $data)
    {
        return $user->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $userRole = Role::where('name', 'user')->first()->id,
            'profession' => $data['profession'],
            'birthday' => $data['birthday'],
            'gender' => $data['gender'],
        ]);
    }


    public function updateUser(User $user, array $data)
    {
        return $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'profession' => $data['profession'],
            'birthday' => $data['birthday'],
            'gender' => $data['gender'],
        ]);
    }

    public function deleteUser(User $user)
    {
        $user->posts()->delete();
        $user->delete();
    }
}
