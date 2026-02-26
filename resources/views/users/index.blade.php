@extends('layouts.app')

@section('content')
    @vite(['resources/css/user/index.css'])
    <div class="users-page-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-content">
                <div class="page-title-section">
                    <h1 class="page-title">
                        <i class="bi bi-people-fill me-2"></i>
                        User Management
                    </h1>
                    <p class="page-subtitle">Manage and monitor all system users</p>
                </div>
                <div class="page-stats">
                    <div class="stat-box">
                        <div class="stat-icon-box stat-icon-primary">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $users->total() }}</span>
                            <span class="stat-text">Total Users</span>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon-box stat-icon-success">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $users->where('type', 0)->count() }}</span>
                            <span class="stat-text">Admins</span>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon-box stat-icon-info">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number">{{ $users->where('type', 1)->count() }}</span>
                            <span class="stat-text">Regular Users</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <div class="alerts-container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-content">
                        <i class="bi bi-check-circle-fill alert-icon"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="alert-content">
                        <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Premium Filter Section -->
        <div class="premium-filter-section">
            <div class="filter-header">
                <h3 class="filter-title">
                    <i class="bi bi-funnel-fill me-2"></i>
                    Filter & Search
                </h3>
                @if(request()->hasAny(['name', 'email', 'dob_from', 'dob_to', 'type']) && request('type') != 'all')
                    <a href="{{ route('users.index') }}" class="btn-clear-all">
                        <i class="bi bi-x-circle me-1"></i>Clear All
                    </a>
                @endif
            </div>
            <form method="GET" action="{{ route('users.index') }}" class="premium-filter-form">

                <!-- Date Range Filter -->
                <div class="filter-group filter-inline">
                    <div class="date-filter-wrapper">
                        <button class="btn date-filter-btn" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-calendar-event me-2"></i>
                            <span class="date-filter-text">
                                @if(request('dob_from') || request('dob_to'))
                                    {{ request('dob_from') ?? 'Start' }} - {{ request('dob_to') ?? 'End' }}
                                @else
                                    Date Range
                                @endif
                            </span>
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </button>
                        <div class="dropdown-menu date-dropdown-menu">
                            <div class="date-range-picker">
                                <div class="date-input-group">
                                    <label class="date-label">
                                        <i class="bi bi-calendar-event me-1"></i>From Date
                                    </label>
                                    <input type="date" class="form-control date-input" name="dob_from"
                                        value="{{ request('dob_from') }}">
                                </div>
                                <div class="date-input-group">
                                    <label class="date-label">
                                        <i class="bi bi-calendar-event me-1"></i>To Date
                                    </label>
                                    <input type="date" class="form-control date-input" name="dob_to"
                                        value="{{ request('dob_to') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Type Filter -->
                <div class="filter-group filter-inline">
                    <div class="select-wrapper">
                        <i class="bi bi-funnel-fill select-icon"></i>
                        <select name="type" class="premium-select" id="type" onchange="this.form.submit()">
                            <option value="all" {{$filters['type'] == 'all' ? 'selected' : '' }} data-text="All Users" data-icon="📇">
                                All Users
                            </option>
                            <option value="1" {{$filters['type']== '1' ? 'selected' : '' }} data-text="User" data-icon="⚙️">
                                User
                            </option>
                            <option value="0" {{$filters['type']== '0' ? 'selected' : '' }} data-text="Admin" data-icon="👥">
                                Admin
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Name Search -->
                <div class="filter-group filter-inline">
                    <div class="input-wrapper">
                        <i class="bi bi-person-fill input-icon"></i>
                        <input type="text" class="premium-input" name="name" placeholder="Search by name..."
                            value="{{ request('name') }}">
                    </div>
                </div>

                <!-- Email Search -->
                <div class="filter-group filter-inline filter-email-group">
                    <div class="email-search-group">
                        <div class="input-wrapper">
                            <i class="bi bi-envelope-fill input-icon"></i>
                            <input type="text" class="premium-input premium-input-email" name="email" placeholder="Search by email..."
                                value="{{ request('email') }}">
                        </div>
                        <button type="submit" class="btn-search-premium">
                            <i class="bi bi-search"></i>
                            <span class="search-text">Search</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Main Content Card -->
        <div class="main-content-card">


            <!-- Table Header -->
            <div class="table-header-section">
                <h3 class="table-title">
                    <i class="bi bi-table me-2"></i>
                    Users List
                </h3>
                <div class="table-actions">
                    <span class="results-count">{{ $users->total() }} results</span>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="table-responsive-wrapper">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th class="col-id">
                                <i class="bi bi-hash me-1"></i>ID
                            </th>
                            <th class="col-name">
                                <i class="bi bi-person me-1"></i>Name
                            </th>
                            <th class="col-email">
                                <i class="bi bi-envelope me-1"></i>Email
                            </th>
                            <th class="hide-on-mobile col-created-user">
                                <i class="bi bi-person-plus me-1"></i>Created User
                            </th>
                            <th class="col-type">
                                <i class="bi bi-shield me-1"></i>Type
                            </th>
                            <th class="col-phone">
                                <i class="bi bi-telephone me-1"></i>Phone
                            </th>
                            <th class="col-dob">
                                <i class="bi bi-calendar me-1"></i>Date of Birth
                            </th>
                            <th class="hide-on-mobile col-address">
                                <i class="bi bi-geo-alt me-1"></i>Address
                            </th>
                            <th class="hide-on-mobile col-created">
                                <i class="bi bi-clock-history me-1"></i>Created
                            </th>
                            <th class="hide-on-mobile col-updated">
                                <i class="bi bi-clock me-1"></i>Updated
                            </th>
                            @if (Auth::check() && Auth::user()->type == 0)
                                <th class="col-action">
                                    <i class="bi bi-gear me-1"></i>Action
                                </th>
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
                                <td>
                                    <i class="bi bi-envelope-fill me-1"></i>
                                    {{ $user->email }}
                                </td>
                                <td class="hide-on-mobile">
                                    <i class="bi bi-person-plus-fill me-1"></i>
                                    {{ optional(\App\Models\User::find($user->created_user_id))->name ?? 'N/A' }}
                                </td>
                                <td>
                                    @if($user->type == 0)
                                        <span class="badge badge-admin">
                                            <i class="bi bi-shield-fill-check me-1"></i>Admin
                                        </span>
                                    @else
                                        <span class="badge badge-user">
                                            <i class="bi bi-person-fill me-1"></i>User
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <i class="bi bi-telephone-fill me-1"></i>
                                    {{ $user->phone }}
                                </td>
                                <td>
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $user->dob ? $user->dob->format('Y-m-d') : 'N/A' }}
                                </td>


                                <td class="hide-on-mobile">
                                    <i class="bi bi-geo-alt-fill me-1"></i>
                                    {{ $user->address }}
                                </td>
                                <td class="hide-on-mobile">
                                    <i class="bi bi-clock-history me-1"></i>
                                    {{ $user->created_at }}
                                </td>
                                <td class="hide-on-mobile">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $user->updated_at }}
                                </td>
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
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content profile-modal-content">
                                    <div class="modal-header profile-modal-header">
                                        <h5 class="modal-title" id="profileModalLabel{{ $user->id }}">
                                            <i class="bi bi-person-circle me-2"></i>User Profile
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body profile-modal-body">
                                        <div class="profile-content">
                                            <!-- Profile Image -->
                                            <div class="profile-image-wrapper">
                                                <div class="profile-image-container">
                                                    <img src="{{ Storage::url($user->profile) }}" alt="Profile Picture"
                                                        class="profile-img-modal">
                                                    @if($user->type == 0)
                                                        <span class="admin-badge-modal">
                                                            <i class="bi bi-shield-fill-check"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="profile-name-section">
                                                    <h4 class="profile-modal-name">{{ $user->name }}</h4>
                                                    @if($user->type == 0)
                                                        <span class="badge badge-admin badge-modal-title">
                                                            <i class="bi bi-shield-fill-check me-1"></i>Admin
                                                        </span>
                                                    @else
                                                        <span class="badge badge-user badge-modal-title">
                                                            <i class="bi bi-person-fill me-1"></i>User
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- User Details Sections -->
                                            <div class="user-details-container">
                                                <!-- Personal Information Section -->
                                                <div class="details-section">
                                                    <h5 class="details-section-title">
                                                        <i class="bi bi-person-badge me-2"></i>Personal Information
                                                    </h5>
                                                    <div class="user-details-grid">
                                                        <div class="detail-item">
                                                            <div class="detail-icon-wrapper">
                                                                <i class="bi bi-envelope-fill detail-icon"></i>
                                                            </div>
                                                            <div class="detail-content">
                                                                <span class="detail-label">Email</span>
                                                                <span class="detail-value">{{ $user->email }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="detail-item">
                                                            <div class="detail-icon-wrapper">
                                                                <i class="bi bi-telephone-fill detail-icon"></i>
                                                            </div>
                                                            <div class="detail-content">
                                                                <span class="detail-label">Phone</span>
                                                                <span class="detail-value">{{ $user->phone }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="detail-item">
                                                            <div class="detail-icon-wrapper">
                                                                <i class="bi bi-calendar-event detail-icon"></i>
                                                            </div>
                                                            <div class="detail-content">
                                                                <span class="detail-label">Date of Birth</span>
                                                                <span class="detail-value">{{ $user->dob ? $user->dob->format('F d, Y') : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="detail-item detail-item-full">
                                                            <div class="detail-icon-wrapper">
                                                                <i class="bi bi-geo-alt-fill detail-icon"></i>
                                                            </div>
                                                            <div class="detail-content">
                                                                <span class="detail-label">Address</span>
                                                                <span class="detail-value">{{ $user->address }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- System Information Section -->
                                                <div class="details-section">
                                                    <h5 class="details-section-title">
                                                        <i class="bi bi-gear-fill me-2"></i>System Information
                                                    </h5>
                                                    <div class="user-details-grid">
                                                        <div class="detail-item">
                                                            <div class="detail-icon-wrapper">
                                                                <i class="bi bi-clock-history detail-icon"></i>
                                                            </div>
                                                            <div class="detail-content">
                                                                <span class="detail-label">Created Date</span>
                                                                <span class="detail-value">{{ $user->created_at->format('F d, Y h:i A') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="detail-item">
                                                            <div class="detail-icon-wrapper">
                                                                <i class="bi bi-clock detail-icon"></i>
                                                            </div>
                                                            <div class="detail-content">
                                                                <span class="detail-label">Updated Date</span>
                                                                <span class="detail-value">{{ $user->updated_at->format('F d, Y h:i A') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="detail-item">
                                                            <div class="detail-icon-wrapper">
                                                                <i class="bi bi-person-plus-fill detail-icon"></i>
                                                            </div>
                                                            <div class="detail-content">
                                                                <span class="detail-label">Created By</span>
                                                                <span class="detail-value">{{ optional(\App\Models\User::find($user->created_user_id))->name ?? 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="detail-item">
                                                            <div class="detail-icon-wrapper">
                                                                <i class="bi bi-pencil-square detail-icon"></i>
                                                            </div>
                                                            <div class="detail-content">
                                                                <span class="detail-label">Updated By</span>
                                                                <span class="detail-value">{{ $user->updatedUser ? $user->updatedUser->name : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer profile-modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                            aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content delete-modal-content">
                                    <div class="modal-header delete-modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Delete Confirmation
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body delete-modal-body">
                                        <div class="warning-icon-wrapper">
                                            <i class="bi bi-exclamation-triangle-fill warning-icon"></i>
                                        </div>
                                        <p class="warning-text">Are you sure you want to delete this user?</p>
                                        <div class="user-info-card">
                                            <div class="info-row">
                                                <span class="info-label"><i class="bi bi-hash me-1"></i>ID:</span>
                                                <span class="info-value">{{ $user->id }}</span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label"><i class="bi bi-person-fill me-1"></i>Name:</span>
                                                <span class="info-value">{{ $user->name }}</span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label"><i class="bi bi-shield-fill-check me-1"></i>Type:</span>
                                                <span class="info-value">
                                                    @if($user->type == 0)
                                                        <span class="badge badge-admin">Admin</span>
                                                    @else
                                                        <span class="badge badge-user">User</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label"><i class="bi bi-envelope-fill me-1"></i>Email:</span>
                                                <span class="info-value">{{ $user->email }}</span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label"><i class="bi bi-telephone-fill me-1"></i>Phone:</span>
                                                <span class="info-value">{{ $user->phone }}</span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label"><i class="bi bi-calendar-event me-1"></i>Date of Birth:</span>
                                                <span class="info-value">{{ $user->dob ? $user->dob->format('Y-m-d') : 'N/A' }}</span>
                                            </div>
                                            <div class="info-row">
                                                <span class="info-label"><i class="bi bi-geo-alt-fill me-1"></i>Address:</span>
                                                <span class="info-value">{{ $user->address }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer delete-modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-delete-gradient">
                                                <i class="bi bi-trash-fill me-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="mobile-cards-view">
                @foreach ($users as $user)
                    <div class="user-card-mobile">
                        <div class="card-mobile-header">
                            <div class="card-mobile-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="card-mobile-title">
                                <h4 class="card-mobile-name">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#profileModal{{ $user->id }}"
                                        class="text-decoration-none">
                                        {{ $user->name }}
                                    </a>
                                </h4>
                                <span class="card-mobile-id">#{{ $user->id }}</span>
                            </div>
                            <div class="card-mobile-badge">
                                @if($user->type == 0)
                                    <span class="badge badge-admin">
                                        <i class="bi bi-shield-fill-check"></i>
                                    </span>
                                @else
                                    <span class="badge badge-user">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-mobile-body">
                            <div class="card-mobile-item">
                                <i class="bi bi-envelope-fill"></i>
                                <span class="card-mobile-label">Email:</span>
                                <span class="card-mobile-value">{{ Str::limit($user->email, 25) }}</span>
                            </div>
                            <div class="card-mobile-item">
                                <i class="bi bi-telephone-fill"></i>
                                <span class="card-mobile-label">Phone:</span>
                                <span class="card-mobile-value">{{ $user->phone }}</span>
                            </div>
                            <div class="card-mobile-item">
                                <i class="bi bi-calendar-event"></i>
                                <span class="card-mobile-label">DOB:</span>
                                <span class="card-mobile-value">{{ $user->dob ? $user->dob->format('Y-m-d') : 'N/A' }}</span>
                            </div>
                            <div class="card-mobile-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span class="card-mobile-label">Address:</span>
                                <span class="card-mobile-value">{{ Str::limit($user->address, 20) }}</span>
                            </div>
                        </div>
                        @if (Auth::check() && Auth::user()->type == 0)
                            <div class="card-mobile-footer">
                                <a href="#" class="btn btn-danger btn-danger-sm btn-mobile-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $user->id }}">
                                    <i class="bi bi-trash-fill me-1"></i>Delete
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
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