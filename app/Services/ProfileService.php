<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function updatePassword(User $user, array $data)
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            return ['error' => 'Current password does not match!'];
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);
        return  ['success' => true];
    }
    public function updateImage(User $user, array $data)
    {
        $imageFile = $data['profile_picture'];
        $imageName = time() . '.' . $imageFile->extension();
        $imagePath = 'profile_images/' . $imageName;
        $imageFile->storeAs('profile_images', $imageName);

        if ($user->profilePicture && $user->profilePicture->image_path) {
            Storage::delete(str_replace('storage/', 'public/', $user->profilePicture->image_path));
        }

        if ($user->profilePicture) {
            $user->profilePicture->update(['image_path' => $imagePath]);
        } else {
            $user->profilePicture()->create([
                'user_id' => $user->id,
                'image_path' => $imagePath
            ]);
        }
        return ['success' => 'Profile image updated successfully!'];
    }
}
?>