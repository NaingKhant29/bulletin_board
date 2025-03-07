<?php

namespace App\Service\User;

use App\Interface\Dao\User\UserDaoInterface;
use App\Interface\Service\User\UserServiceInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserService implements UserServiceInterface
{
    protected $userDao;

    public function __construct(UserDaoInterface $userDao)
    {
        $this->userDao = $userDao;
    }

    public function getFilteredUsers($filters)
    {
        return $this->userDao->getUsers($filters);
    }
    public function validateRegistration(array $data)
    {
        return Validator::make($data, [
            'dob' => [
                'required',
                'date',
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    $minYear = 1900;
                    $dob = strtotime($value);
                    $year = date('Y', $dob);
                    $age = date('Y') - $year;

                    // Ensure the birth year is valid (1900+)
                    if ($year < $minYear) {
                        $fail('You are not a human!');
                    }

                    // Ensure the user is at least 14 years old
                    if ($age < 14) {
                        $fail('You must be at least 14 years old to register.');
                    }
                }
            ],

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'integer', 'in:0,1'],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'file', 'mimes:jpeg,png,jpg,gif', 'max:8048'],
        ], [
            'dob.required' => 'Please provide your date of birth.',
            'dob.date' => 'The date of birth is not a valid date.',
            'dob.before_or_equal' => 'You are a time traveller. You are not allowed to access!',
            'dob.age' => 'You must be at least 14 years old to register.',

            'name.required' => 'Please provide your full name.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name cannot be longer than 255 characters.',

            'email.required' => 'We need your email to contact you.',
            'email.email' => 'Email format is invalid.',
            'email.max' => 'The email cannot be longer than 255 characters.',
            'email.unique' => 'The email is already taken.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password and password confirmation does not match.',

            'type.required' => 'Please select the user type.',
            'type.in' => 'The user type must be either Admin (0) or User (1).',

            'phone.max' => 'Phone number cannot be longer than 15 characters.',
            'address.max' => 'The address cannot be longer than 255 characters.',

            'image.required' => 'Please upload a profile picture.',
            'image.file' => 'The profile picture must be a file.',
            'image.mimes' => 'The profile picture must be a JPEG, PNG, JPG, or GIF image.',
            'image.max' => 'The profile picture cannot be larger than 8MB.',
        ]);
    }

    /**
     * Register a new user
     *
     * @param array $data
     * @return \App\Models\User
     */
    public function confirmUser(array $data)
    {
        $validator = $this->validateRegistration($data);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $validated = $validator->validated();

        $profilePath = null;
        if (!empty($data['image'])) {
            $profilePath = $data['image']->store('profiles', 'public');
        }

        session([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'password_confirm' => $data['password_confirmation'],
            'type' => $validated['type'],
            'phone' => $validated['phone'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'address' => $validated['address'] ?? null,
            'image' => $profilePath,
        ]);

        return session()->all();
    }

    /**
     * Register a new user.
     *
     * @return void
     */
    public function registerUser(): void
    {
        $data = session()->all();
        $currentUserId = Auth::id();

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'type' => $data['type'],
            'phone' => $data['phone'],
            'dob' => $data['dob'],
            'address' => $data['address'],
            'profile' => $data['image'] ?? null,
            'created_user_id' => Auth::id(),
            'updated_user_id' => Auth::id(),
        ];

        $this->userDao->createUser($userData);
    }

    /**
     * Get the authenticated user's profile.
     *
     * @return User
     */
    public function editProfile(): User
    {
        return Auth::user();
    }

    /**
     * Update the profile of a user.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateProfile(int $id, array $data): bool
    {
        $user = $this->userDao->findById($id);

        if (Auth::user()->type == 0) {
            $data['type'] = $data['type'] ?? $user->type;
        }

        $isEdited = $user->name !== $data['name'] ||
            $user->email !== $data['email'] ||
            $user->phone !== $data['phone'] ||
            $user->dob !== $data['dob'] ||
            $user->address !== $data['address'] ||
            isset($data['profile']);

        if ($isEdited) {
            $data['updated_user_id'] = Auth::id();
            $data['updated_at'] = now()->format('d-m-Y H:i:s');
        }

        return $this->userDao->updateUser($user, $data);
    }

    /**
     * Change the authenticated user's password.
     *
     * @param array $data
     * @return bool
     */
    public function changePassword(array $data): bool
    {
        $user = Auth::user();

        if (!Hash::check($data['current_password'], $user->password)) {
            return false;
        }

        return $this->userDao->updatePassword($user, $data['new_password']);
    }
}
