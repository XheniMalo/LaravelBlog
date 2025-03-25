<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\UserUpdatedNotification;

class UserObserver
{
    public function updated(User $user): void
    {
        $changed = [];

        foreach ($user->getDirty() as $field => $newValue) {
            if ($field === 'password') {
                continue;
            }

            $changed[$field] = [
                'from' => $user->getRawOriginal($field) ?: '(empty)',
                'to' => $newValue ?: '(empty)',
            ];
        }

        if (!empty($changed)) {
            $admins = User::role('admin')->get();

           foreach($admins as $admin){
            $admin->notify(new UserUpdatedNotification($user, $changed));
           }
        }

    }
}
