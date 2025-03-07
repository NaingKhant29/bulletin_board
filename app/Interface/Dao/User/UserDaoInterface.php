<?php

namespace App\Interface\Dao\User;

use App\Models\User;

interface UserDaoInterface
{
    /**
     * @param array $filter
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUsers($filters);

    /**
     * @param array $data
     * @return \App\Models\User
     */
    public function createUser(array $data);

    /**
     * @param int $id
     * @return \App\Models\User|null
     */
    public function findById($id): ?User;

    /**
     * @param \App\Models\User $user
     * @param array $data
     * @return bool
     */
    public function updateUser(User $user, array $data): bool;

    /**
     * @param \App\Models\User $user
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword(User $user, string $newPassword): bool;
}
