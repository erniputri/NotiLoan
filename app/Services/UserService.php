<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserService
{
    // User baru dari panel hanya boleh dibuat sebagai admin biasa agar hak super admin tetap terkendali.
    public function createAdmin(array $data): User
    {
        return DB::transaction(function () use ($data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['sap'],
                'role' => User::ROLE_ADMIN,
                'password' => $data['password'],
            ]);
        });
    }

    // Update dibatasi ke akun admin biasa supaya akun super admin tetap menjadi akun sistem hasil seeder.
    public function updateAdmin(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $managedUser = User::lockForUpdate()->findOrFail($user->id);
            $this->guardManageable($managedUser);

            $payload = [
                'name' => $data['name'],
                'email' => $data['sap'],
            ];

            if (! empty($data['password'])) {
                $payload['password'] = $data['password'];
            }

            $managedUser->update($payload);

            return $managedUser->refresh();
        });
    }

    public function deleteAdmin(User $user, User $actingUser): void
    {
        DB::transaction(function () use ($user, $actingUser) {
            $managedUser = User::lockForUpdate()->findOrFail($user->id);
            $this->guardManageable($managedUser);

            if ($managedUser->is($actingUser)) {
                throw ValidationException::withMessages([
                    'user' => 'Akun yang sedang dipakai tidak bisa dihapus.',
                ]);
            }

            $managedUser->delete();
        });
    }

    private function guardManageable(User $user): void
    {
        if ($user->isSuperAdmin()) {
            throw ValidationException::withMessages([
                'user' => 'Akun super admin dikelola dari seeder sistem dan tidak dapat diubah dari halaman ini.',
            ]);
        }
    }
}
