@extends('layouts.app')

@section('content')
    @vite(['resources/css/app.js'])
    @vite(['resources/css/confirm.css'])
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <h4 class="card-header">{{ __('Change Password') }}</h4>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.pwupdate') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="current_password" class="col-md-4 col-form-label text-md-end">{{ __('Current Password') }}</label>
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror pe-5" name="current_password">
                                        <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3 toggle-password" data-target="current_password" style="cursor: pointer;"></i>
                                    </div>
                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="row mb-3">
                                <label for="new_password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror pe-5" name="new_password">
                                        <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3 toggle-password" data-target="new_password" style="cursor: pointer;"></i>
                                    </div>
                                    @error('new_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="row mb-3">
                                <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-end">{{ __('Confirm New Password') }}</label>
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <input id="new_password_confirmation" type="password" class="form-control pe-5" name="new_password_confirmation">
                                        <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3 toggle-password" data-target="new_password_confirmation" style="cursor: pointer;"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Change Password') }}
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
