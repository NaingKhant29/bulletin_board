@extends('layouts.app')

@section('content')
@vite(['resources/css/confirm.css'])
@vite(['resources/css/user/profileedit.css'])
    <div class="container">
        <div class="card shadow border-0">
                <h4 class="card-header txt-lft">Profile Edit</h4>
            <div class="card-body">
                <form id="editProfileForm" method="POST" enctype="multipart/form-data"
                    action="{{ route('profile.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">Name<span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">E-mail Address<span
                                class="text-danger">*</span></label>
                        <div class="col-md-6">
                            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                value="{{ old('email', $user->email) }}">
                            @error('email')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="row mb-3">
                        <label for="type" class="col-md-4 col-form-label text-md-end">Type</label>
                        <div class="col-md-6">
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type"
                                {{ Auth::user()->type == 1 ? 'disabled' : '' }}>
                                <option value="0" {{ $user->type == 0 ? 'selected' : '' }}>Admin</option>
                                <option value="1" {{ $user->type == 1 ? 'selected' : '' }}>User</option>
                            </select>
                            @error('type')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="row mb-3">
                        <label for="phone" class="col-md-4 col-form-label text-md-end">Phone</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>

                    <!-- Date of Birth -->
                    <div class="row mb-3">
                        <label for="dob" class="col-md-4 col-form-label text-md-end">Date of Birth</label>
                        <div class="col-md-6">
                            <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob"
                                name="dob" value="{{ old('dob', $user->dob) }}">
                            @error('dob')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="row mb-3">
                        <label for="address" class="col-md-4 col-form-label text-md-end">Address</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                                name="address" value="{{ old('address', $user->address) }}">
                            @error('address')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>

                    <!-- Old Profile -->
                    <div class="row mb-3">
                        <label for="old-profile" class="col-md-4 col-form-label text-md-end">Old Profile</label>
                        <div class="col-md-6">
                            <img id="profilePreview" class="img-thumbnail"
                                src="{{ $user->profile ? asset('storage/' . $user->profile) : 'https://via.placeholder.com/150' }}"
                                alt="Profile" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- New Profile -->
                    <div class="row mb-3">
                        <label for="profile" class="col-md-4 col-form-label text-md-end">New Profile</label>
                        <div class="col-md-6">
                            <input class="form-control @error('profile') is-invalid @enderror" type="file" id="profile"
                                name="profile" accept="image/*">
                            @error('profile')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4 ">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button type="button" class="btn btn-info colorfff" id="clearFormBtn">Clear</button>
                            <a href="{{ route('users.pwchange') }}" class="btn btn-link">Change Password</a>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#clearFormBtn").click(function() {
                $("#editProfileForm").find("input, select, textarea").val("");
                $("#editProfileForm").find("input[type=checkbox], input[type=radio]").prop("checked", false);
                $("#editProfileForm").find("select").prop("selectedIndex", 0);
                $("#profile").val("");
                $("#profilePreview").attr("src", "https://via.placeholder.com/150");
            });
    
            $("#profile").change(function(event) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $("#profilePreview").attr("src", e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endsection
