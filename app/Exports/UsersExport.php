<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array{
        return ["Name","Email","Profession","Birthday","Gender","Role"];
    }
    public function collection()
    {
        $users = User::with('roles')->get();

        return $users->map(function ($user) {
            return [
                $user->name,
                $user->email,
                $user->profession,
                $user->birthday,
                $user->gender instanceof \BackedEnum ? $user->gender->value : (string)$user->gender,
                $user->roles->pluck('name')->implode(', ') ?: 'No Role',
            ];
        });
    }
}
