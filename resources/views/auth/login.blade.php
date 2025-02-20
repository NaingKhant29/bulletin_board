@vite(['resources/sass/app.scss', 'resources/js/app.js'])

@vite(['resources/css/dexin.css'])
@vite(['resources/css/confirm.css'])
@vite(['resources/css/upload.css'])
@vite(['resources/css/login.css'])

<body>
    <div class="container bg-login">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card">


                    <h3 class="card-header" style="text-align: center">{{ __('Login') }}</h3>


                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}" class="form">
                            @csrf

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 margin-lft">

                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>

                            </div>


                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary btn-loginn fgt-pass">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 margin-lft">
                                @if (Route::has('password.request'))
                                    <a class="fgt-pass" href="{{ route('password.request') }}">
                                        {{ __('Forgotten Password?') }}
                                    </a>
                                @endif
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
