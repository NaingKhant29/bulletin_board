@vite(['resources/css/confirm.css'])
@vite(['resources/css/login.css'])
@vite(['resources/css/app.css'])
@vite(['resources/js/app.js'])

<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="login-container">
        <h4 class="card-header mb-4">{{ __('Login') }}</h4>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <div class="input-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                           name="password" autocomplete="current-password">
                        <i class="bi bi-eye-slash position-absolute end-0 top-50 translate-middle-y me-3 toggle-password" data-target="password" style="cursor: pointer;"></i>
             
                </div>
                @error('password')
                    <span class="invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-custom">{{ __('Login') }}</button>
            </div>

            <div class="text-center mt-3">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary for-get">
                        {{ __('Forgotten Password?') }}
                    </a>
                @endif
            </div>
        </form>
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

