<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | BBMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --theme-color: #5f6f82; /* ✅ Subtitle color (bluish gray) */
        }

        body {
            background: #f5f7fb;
        }

        .login-card {
            border-radius: 12px;
        }

        .form-outline {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .form-outline input {
            width: 100%;
            padding: 10px 38px 10px 12px;
            border: 1.5px solid var(--theme-color);
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            background: #fff;
            color: var(--theme-color); /* ✅ all text color */
        }

        /* Autofill fix */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-text-fill-color: var(--theme-color) !important;
            transition: background-color 5000s ease-in-out 0s;
            box-shadow: 0 0 0px 1000px #fff inset !important;
        }

        .form-outline label {
            position: absolute;
            top: 50%;
            left: 12px;
            color: var(--theme-color);
            background: #fff;
            padding: 0 6px;
            font-size: 13px;
            transform: translateY(-50%);
            pointer-events: none;
            transition: 0.2s ease;
        }

        .form-outline input:focus + label,
        .form-outline input:not(:placeholder-shown) + label {
            top: 0;
            font-size: 11px;
            color: var(--theme-color);
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--theme-color);
            font-size: 16px;
            user-select: none;
        }

        .logo {
            max-width: 110px;
            height: auto;
        }

        .site-title {
            font-weight: 700;
            font-size: 1.3rem;
            margin-top: 10px;
            margin-bottom: 2px;
            color: var(--theme-color);
        }

        .site-subtitle {
            font-size: 0.9rem;
            color: var(--theme-color); /* ✅ this is the reference color */
        }

        .form-check-label {
            color: var(--theme-color);
        }

        .btn-primary {
            background-color: var(--theme-color);
            border-color: var(--theme-color);
        }

        .btn-primary:hover {
            background-color: #4f5d6d;
            border-color: #4f5d6d;
        }

        a {
            color: var(--theme-color);
        }

        a:hover {
            color: #4f5d6d;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow border-0 login-card">
                <div class="card-body p-4">

                    {{-- Logo + Title --}}
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/bblogo.png') }}" alt="BBMS Logo" class="logo mb-2">
                        <div class="site-title">BBMS</div>
                        <div class="site-subtitle">Bulletin Board Management System</div>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="form-outline">
                            <input type="email"
                                   name="email"
                                   id="email"
                                   placeholder=" "
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>
                            <label for="email">Email Address</label>
                        </div>

                        {{-- Password --}}
                        <div class="form-outline">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   placeholder=" "
                                   required>
                            <label for="password">Password</label>

                            <!-- Eye icon -->
                            <span id="togglePassword" class="toggle-password">👁️</span>
                        </div>

                        {{-- Remember me --}}
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        {{-- Submit --}}
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3">
                                Sign In
                            </button>
                        </div>

                        {{-- Forgot password --}}
                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    Forgot your password?
                                </a>
                            @endif
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });
</script>

</body>
</html>
