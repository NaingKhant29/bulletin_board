@extends('layouts.app')

@section('content')
    @vite(['resources/css/app.js'])
    @vite(['resources/css/confirm.css'])
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h4 class="card-header">{{ __('Register') }}</h4>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.confirm') }}" enctype="multipart/form-data">
                            @csrf


                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" autocomplete="name" autofocus>

                                    @error('name')
                                        <strong class="text-danger">{{ $message }}</strong>
                                    @enderror

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}">

                                    @error('email')
                                        <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                        
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <input id="password" type="password"
                                            class="form-control pe-5 @error('password') is-invalid @enderror"
                                            name="password" autocomplete="new-password"
                                            style="@error('password') background-image: none !important; @enderror">
                        
                                        <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3 toggle-password"
                                            data-target="password" style="cursor: pointer;"></i>
                                    </div>
                        
                                    @error('password')
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password Confirmation') }}</label>

                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <input id="password-confirm" type="password" class="form-control pe-5"
                                            name="password_confirmation" autocomplete="new-password">
                                        <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3 toggle-password"
                                            data-target="password-confirm" style="cursor: pointer;"></i>
                                    </div>
                                </div>
                            </div>




                            <div class="row mb-3">
                                <label for="type"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Type') }}</label>

                                <div class="col-md-6">
                                    <select id="type" class="form-control" name="type">
                                        <option value="0" {{ old('type') == 0 ? 'selected' : '' }}>Admin</option>
                                        <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="phone"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>
                                <div class="col-md-6">
                                    <input id="phone" type="text"
                                        class="form-control @error('phone') is-invalid @enderror" name="phone"
                                        value="{{ old('phone') }}">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="dob"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Date of Birth') }}</label>
                                <div class="col-md-6">
                                    <input id="dob" type="date"
                                        class="form-control @error('dob') is-invalid @enderror" name="dob"
                                        value="{{ old('dob') }}">
                                    @error('dob')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="dob"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Address') }}</label>
                                <div class="col-md-6">
                                    <input id="address" type="text"
                                        class="form-control @error('dob') is-invalid @enderror" name="address"
                                        value="{{ old('address') }}">
                                    @error('address')
                                        <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="profile"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Profile') }}</label>
                                <div class="col-md-6">
                                    <input id="profile" type="file"
                                        class="form-control @error('image') is-invalid @enderror" name="image"
                                        accept="image/*">

                                    @error('image')
                                        <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>



                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $(document).ready(function() {
                $(".toggle-password").click(function() {
                    let target = $("#" + $(this).data("target"));
                    let icon = $(this);

                    if (target.attr("type") === "password") {
                        target.attr("type", "text");
                        icon.removeClass("bi-eye-slash").addClass("bi-eye");
                    } else {
                        target.attr("type", "password");
                        icon.removeClass("bi-eye").addClass("bi-eye-slash");
                    }
                });
            });
        });
    </script>
@endsection
