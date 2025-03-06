<?php

namespace App\Interface\Dao\User;

use App\Models\User;

interface UserDaoInterface
{
    public function getUsers($filters);
    public function createUser(array $data);
    public function findById($id): ?User;
    public function updateUser(User $user, array $data): bool;
    public function updatePassword(User $user, string $newPassword): bool;
}
