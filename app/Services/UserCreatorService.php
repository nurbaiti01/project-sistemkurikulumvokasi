<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserCreatorService
{
    public function createOrGet(string $email, string $name, string $defaultPassword = '12345678'): User
    {
        return User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($defaultPassword)
            ]
        );
    }

    public function attachToPivot($model, User $user): void
    {
        if (method_exists($model, 'users')) {
            // syncWithoutDetaching agar relasi lama tidak hilang
            $model->users()->syncWithoutDetaching($user->id);
        }
    }

    public function assignRoles(User $user, $roles)
    {
        // roles bisa int, array, collection
        $user->roles()->sync($roles);
    }
}
