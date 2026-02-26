@extends('layouts.app')

@section('content')
@vite(['resources/css/app.js'])
@vite(['resources/css/confirm.css'])
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <h4 class="card-header">{{ __('Reset Password') }}</h4>

                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ request()->query('email') }}">
                        
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror pe-5" name="password"  style="@error('password') background-image: none !important; @enderror">
                                        <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3 toggle-password" data-target="password" style="cursor: pointer;"></i>
                                    </div>
                                    @error('password')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="row mb-3">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                                <div class="col-md-6">
                                    <div class="position-relative">
                                        <input id="password-confirm" type="password" class="form-control pe-5" name="password_confirmation" >
                                        <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3 toggle-password" data-target="password-confirm" style="cursor: pointer;"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Reset Password') }}
                                    </button>
                                </div>
                            </div>
                        </form>
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
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
