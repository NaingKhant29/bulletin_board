<?php

namespace App\Interface\Service\User;

use App\Models\User;

interface UserServiceInterface
{
    public function getFilteredUsers($filters);
    public function validateRegistration(array $data);
    public function confirmUser(array $data);
    public function registerUser();
    public function editProfile(): User;
    public function updateProfile(int $id, array $data): bool;
    public function changePassword(array $data): bool;
    
}
