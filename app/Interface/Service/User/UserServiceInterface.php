<?php

namespace App\Interface\Service\User;

use App\Models\User;

interface UserServiceInterface
{
/**
 * @param array $filters
 * @return \Illuminate\Pagination\LengthAwarePaginator
 */
public function getFilteredUsers($filters);

/**
 * @param array $data
 * @return array
 */
public function validateRegistration(array $data);

/**
 * @param array $data
 * @return array
 */
public function confirmUser(array $data);

/**
 * @return void
 */
public function registerUser();

/**
 * @return \App\Models\User
 */
public function editProfile(): User;

/**
 * @param int $id
 * @param array $data
 * @return bool
 */
public function updateProfile(int $id, array $data): bool;

/**
 * @param array $data
 * @return bool
 */
public function changePassword(array $data): bool;   
}
