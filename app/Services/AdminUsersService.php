<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class AdminUsersService
{

    public function getUsersDataTable()
    {
        $users = User::with('roles')->get();

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
            'profession' => $data['profession'],
            'birthday' => $data['birthday'],
            'gender' => $data['gender'],
        ])->assignRole('User');
    }


    public function updateUser(User $user, array $data,  $role = null)
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'profession' => $data['profession'],
            'birthday' => $data['birthday'],
            'gender' => $data['gender'],
        ]);
        if (!empty($role)) {
            $user->syncRoles($role);
        }
        return $user;
    }

    public function deleteUser(User $user)
    {
        $user->posts()->delete();
        $user->delete();
    }
}
