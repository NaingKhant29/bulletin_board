<?php

namespace App\Dao\User;

use App\Models\User;
use App\Interface\Dao\User\UserDaoInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserDao implements UserDaoInterface
{
    /**
     * Retrieve users based on provided filters with pagination.
     *
     * @param array 
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUsers($filters)
    {
        $query = User::query();

        if (isset($filters['name']) && $filters['name']) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['email']) && $filters['email']) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (isset($filters['dob_from']) && isset($filters['dob_to'])) {
            $query->whereBetween('dob', [$filters['dob_from'], $filters['dob_to']]);
        } elseif (isset($filters['dob_from'])) {
            $query->where('dob', '>=', $filters['dob_from']);
        } elseif (isset($filters['dob_to'])) {
            $query->where('dob', '<=', $filters['dob_to']);
        }

        if (isset($filters['type']) && $filters['type'] !== 'all' && in_array($filters['type'], ['0', '1'], true)) {
            $query->where('type', (int) $filters['type']);
        }

        return $query->paginate(10);
    }

    /**
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return User::create($data);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function findById($id): ?User
    {
        return User::findOrFail($id);
    }

    /**
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function updateUser(User $user, array $data): bool
    {
        if (isset($data['profile'])) {
            if ($user->profile) {
                Storage::delete('public/' . $user->profile);
            }
            $data['profile'] = $data['profile']->store('profiles', 'public');
        }

        return $user->update($data);
    }

    /**
     * @param User $user
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword(User $user, string $newPassword): bool
    {
        return $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }
}
