<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Http\Requests\PasswordRequest;
use App\Models\User;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ProfilePictureRequest;
use App\Services\ProfileService;

class UserProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index()
    {
        return view('user.profile');
    }

    public function show()
    {
        return view('user.profile');
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
        $result = $this->profileService->updatePassword($user, $request->validated());
        
        if (isset($result['error'])) {
            return redirect()->back()->withErrors(['current_password' => $result['error']]);
        }

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updateImage(User $user, ProfilePictureRequest $request)
    {
        $this->profileService->updateImage($user, $request->validated());

        return back()->with('success', 'Profile picture updated successfully.');
    }
}
