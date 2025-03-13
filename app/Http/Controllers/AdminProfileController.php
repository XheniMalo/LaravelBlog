<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Services\ProfileService;
use App\Enums\Gender;

class AdminProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index(User $user)
    {
        return view('admin.profile', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.password', compact('user'));
    }
    
    public function updateProfile(User $user, UpdateProfileRequest $request)
    {

        $validated=$request->validated();
        $gender = Gender::from($validated['gender']);

        $user->update([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'profession'=>$validated['profession'],
            'birthday'=>$validated['birthday'],
            'gender'=> $gender->value
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(User $user, PasswordRequest $request)
    {
        $this->profileService->updatePassword($user, $request->validated());

        return back()->with('success', 'Password updated successfully');
    }
}
