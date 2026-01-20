<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Create a new user with validated data.
     */
    public function create(array $validated): User
    {
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->is_admin = $validated['is_admin'] ?? false;
        $user->save();

        return $user;
    }

    /**
     * Update an existing user with validated data.
     */
    public function update(User $user, array $validated): User
    {
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->is_admin = $validated['is_admin'] ?? false;
        $user->save();

        return $user;
    }

    /**
     * Delete a user.
     */
    public function delete(User $user): void
    {
        $user->delete();
    }
}
