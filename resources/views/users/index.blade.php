@extends('layouts.app')

@section('content')
    @vite(['resources/css/user/index.css'])
    <div class="container">
        <div class="user-card">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif


            <!-- Search Form -->
            <form method="GET" action="{{ route('users.index') }}" class="form-filter">

                <!-- Calendar Dropdown -->
                <div class="wd-dd">
                    <button class="btn btn-outline-secondary dropdown-toggle dropdown-calendar" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar3"></i>
                    </button>
                    <div class="dropdown-menu">
                        <div style="display: flex !important; gap: 4px !important;">
                            <div style="flex: 1 !important;">
                                <small style="font-size: 10px !important; color: #666 !important;">From</small>
                                <input type="date" class="form-control" name="dob_from"
                                    style="font-size: 11px !important; padding: 4px !important; height: 28px !important;"
                                    value="{{ request('dob_from') }}">
                            </div>
                            <div style="flex: 1 !important;">
                                <small style="font-size: 10px !important; color: #666 !important;">To</small>
                                <input type="date" class="form-control" name="dob_to"
                                    style="font-size: 11px !important; padding: 4px !important; height: 28px !important;"
                                    value="{{ request('dob_to') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="type-wd">
                    <select name="type" class="mb-type" id="type" onchange="this.form.submit()">
                        <option value="all" {{ $type == 'all' ? 'selected' : '' }} data-text="All Users" data-icon="📇">
                            All
                            Users</option>
                        <option value="0" {{ $type == '0' ? 'selected' : '' }} data-text="User" data-icon="⚙️">User
                        </option>
                        <option value="1" {{ $type == '1' ? 'selected' : '' }} data-text="Admin" data-icon="👥">Admin
                        </option>
                    </select>
                </div>

                <div class="name-wd">
                    <!-- Name Input -->
                    <input type="text" class="mb-name-search" name="name" placeholder="Name"
                        value="{{ request('name') }}">
                </div>
                <div class="email-wd">
                    <!-- Email Search with Button -->
                    <input type="text" class="mb-email-search-input" name="email" placeholder="Email"
                        value="{{ request('email') }}">
                    <button type="submit" class="btn btn-primary beside-email-search-btn search-btn-wd">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>


            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="hide-on-mobile">Created User</th>
                        <th>Type</th>
                        <th>Phone</th>
                        <th>Date of Birth</th>
                        <th class="hide-on-mobile">Address</th>
                        <th class="hide-on-mobile">Created_date</th>
                        <th class="hide-on-mobile">Updated_date</th>
                        @if (Auth::check() && Auth::user()->type == 0)
                            <th>Operation</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#profileModal{{ $user->id }}"
                                    class="text-decoration-none">
                                    {{ $user->name }}
                                </a>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td class="hide-on-mobile">
                                {{ optional(\App\Models\User::find($user->created_user_id))->name ?? 'N/A' }}</td>
                            <td>{{ $user->type == 0 ? 'Admin' : 'User' }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->dob }}</td>
                            <td class="hide-on-mobile">{{ $user->address }}</td>
                            <td class="hide-on-mobile">{{ $user->created_at }}</td>
                            <td class="hide-on-mobile">{{ $user->updated_at }}</td>
                            @if (Auth::check() && Auth::user()->type == 0)
                                <td>
                                    <a href="#" class="btn btn-danger btn-danger-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $user->id }}">
                                        Delete
                                    </a>
                                </td>
                            @endif
                        </tr>

                        <!-- Profile Modal -->
                        <div class="modal fade" id="profileModal{{ $user->id }}" tabindex="-1"
                            aria-labelledby="profileModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #40a3a7; color: #fff;">
                                        <h5 class="modal-title" id="profileModalLabel{{ $user->id }}">User Profile</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex align-items-center">
                                            <!-- Profile Image -->
                                            <div class="profile-img-container mr-4 flex-start">
                                                <img src="{{ Storage::url($user->profile) }}" alt="Profile Picture"
                                                    class="profile-img mb-3 rounded-circle" width="150">
                                            </div>

                                            <!-- User Details -->
                                            <div class="user-details">
                                                <p><strong>Name:</strong> {{ $user->name }}</p>
                                                <p><strong>Type:</strong> {{ $user->type == 0 ? 'Admin' : 'User' }}</p>
                                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                                <p><strong>Phone:</strong> {{ $user->phone }}</p>
                                                <p><strong>Date of Birth:</strong> {{ $user->dob }}</p>
                                                <p><strong>Address:</strong> {{ $user->address }}</p>
                                                <p><strong>Created Date:</strong>
                                                    {{ $user->created_at->format('Y-m-d H:i:s') }}</p>

                                                <p><strong>Created User:</strong>
                                                    {{ $user->createUser ? $user->createUser->created_user_id : 'N/A' }}
                                                </p>

                                                <p><strong>Updated Date:</strong>
                                                    {{ $user->updated_at->format('Y-m-d H:i:s') }}</p>

                                                <p><strong>Updated User:</strong>
                                                    {{ $user->updatedUser ? $user->updatedUser->name : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                            aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Delete
                                            Confirmation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="warning">Are you sure you want to delete this user?</p>
                                        <p><strong>ID:</strong> {{ $user->id }}</p>
                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                        <p><strong>Type :</strong>{{ $user->type == 0 ? 'Admin' : 'User' }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                        <p><strong> Phone :</strong> {{ $user->phone }}</p>
                                        <p><strong>Date of Birth :</strong>{{ $user->dob }}</p>
                                        <p><strong>Address :</strong>{{ $user->address }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>

            </table>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
    <script>
        function updateDropdownDisplay() {
            let select = document.getElementById('type');
            let isMobile = window.innerWidth <= 768; 

            for (let option of select.options) {
                if (isMobile) {
                    option.textContent = option.getAttribute('data-icon'); 
                } else {
                    option.textContent = option.getAttribute('data-text');
                }
            }
        }

        // Run on page load and on resize
        updateDropdownDisplay();
        window.addEventListener('resize', updateDropdownDisplay);
    </script>
@endsection