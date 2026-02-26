@extends('layouts.app')

@section('content')
@vite(['resources/css/app.js'])
@vite(['resources/css/confirm.css'])
@vite(['resources/css/register.css'])

<div class="container" style="max-width:900px; margin-top:40px; margin-bottom:60px;">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card" style="
                border:none;
                border-radius:18px;
                overflow:hidden;
                box-shadow:0 20px 40px rgba(0,0,0,0.10);
                background:#ffffff;
            ">

                <!-- Header -->
                <div style="
                    padding:18px 24px;
                    background:#f8fafc;
                    border-bottom:1px solid #e5e7eb;
                    display:flex;
                    align-items:center;
                    justify-content:space-between;
                ">
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-size:18px; font-weight:600; color:#323653;">
                            Register User
                        </span>
                        <span style="font-size:12px; color:#6b7280;">
                            Create a new user account
                        </span>
                    </div>
                    <i class="bi bi-person-plus" style="font-size:20px; color:#5f6f82;"></i>
                </div>

                <!-- Body -->
                <div style="padding:28px; background:#f9fafb;">

                    <form method="POST" action="{{ route('users.confirm') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Enter full name">
                            </div>
                            @error('name')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="example@mail.com">
                            </div>
                            @error('email')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="Minimum 8 characters">
                                <span class="input-group-text toggle-password" data-target="password" style="cursor:pointer;">
                                    <i class="bi bi-eye-slash"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <select id="type" class="form-control" name="type">
                                    <option value="0" {{ old('type') == 0 ? 'selected' : '' }}>Admin</option>
                                    <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>User</option>
                                </select>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input id="phone" type="text"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="09xxxxxxxx">
                            </div>
                            @error('phone')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input id="dob" type="date"
                                    class="form-control @error('dob') is-invalid @enderror"
                                    name="dob"
                                    value="{{ old('dob') }}"
                                    placeholder="YYYY-MM-DD">
                            </div>
                            @error('dob')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input id="address" type="text"
                                    class="form-control @error('address') is-invalid @enderror"
                                    name="address"
                                    value="{{ old('address') }}"
                                    placeholder="Enter address">
                            </div>
                            @error('address')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Profile Image -->
                        <div class="mb-4">
                            <label class="form-label">Profile <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input id="profile" type="file"
                                    class="form-control @error('image') is-invalid @enderror"
                                    name="image" accept="image/*">
                            </div>
                            @error('image')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div style="display:flex; justify-content:flex-end;">
                            <button type="submit" style="
                                height:44px;
                                padding:0 28px;
                                border-radius:12px;
                                border:none;
                                background:#4f46e5;
                                color:#fff;
                                font-size:14px;
                                font-weight:500;
                                cursor:pointer;
                                box-shadow:0 6px 14px rgba(79,70,229,0.35);
                            ">
                                <i class="bi bi-check-circle me-1"></i> Register
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".toggle-password").forEach(function(btn) {
        btn.addEventListener("click", function() {
            let target = document.getElementById(this.dataset.target);
            let icon = this.querySelector("i");

            if (target.type === "password") {
                target.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                target.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        });
    });
});
</script>
@endsection
