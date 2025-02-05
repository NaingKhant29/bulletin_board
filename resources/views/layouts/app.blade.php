<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bulletin_board') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> --}}

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @vite(['resources/css/app.css'])
    @vite(['resources/css/login.css'])
    @vite(['resources/css/dexin.css'])
    @vite(['resources/css/confirm.css'])
    @vite(['resources/css/upload.css'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Bulletin_board') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link text-success" href="{{ route('users.dexin') }}">
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-success" href="{{ url('/posts') }}">
                                Posts
                            </a>
                        </li>
                       
                    </ul>

                 <!-- Updated Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    @if(Auth::check() && Auth::user()->type == 0) 
                        <li class="nav-item">
                            <a class="nav-link text-success" href="{{ route('users.show') }}">
                                <i class="bi bi-plus-circle"></i> Create User
                            </a>
                        </li>
                    @endif

                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif   
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                                <i class="bi bi-gear ms-2"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                                    <i class="bi bi-person"></i> {{ __('Profile') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>

                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="wrapper">
            @yield('content')
            </div>
        </main>
        @auth
       <!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="profileModalLabel">
                    <i class="bi bi-person-circle me-2"></i> Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Profile Image -->
                <div class="text-center mb-4">
                    <img src="{{ Auth::user()->profile ? asset('storage/' . Auth::user()->profile) : asset('images/default-profile.png') }}" 
                         alt="Profile Picture" class="rounded-circle border border-3 border-success" 
                         width="120" height="120">
                </div>

                <!-- Profile Details -->
                <div class="card shadow-sm p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Name:</strong> {{ Auth::user()->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Type:</strong> {{ Auth::user()->type == 0 ? 'Admin' : 'User' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Email:</strong> {{ Auth::user()->email }}
                        </li>
                        <li class="list-group-item">
                            <strong>Phone:</strong> {{ Auth::user()->phone ?? 'N/A' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Date of Birth:</strong> 
                            {{ Auth::check() && Auth::user()->dob ? Auth::user()->dob->format('Y-m-d') : 'N/A' }}
                        </li>
                        
                        <li class="list-group-item">
                            <strong>Address:</strong> {{ Auth::user()->address ?? 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer d-flex justify-content-between">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary rounded-pill shadow-sm">
                    <i class="bi bi-pencil-square"></i> Edit Profile
                </a>
                
                <button type="button" class="btn btn-secondary rounded-pill shadow-sm" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>
        @endauth


        <footer class="footer container">
            <section class="footer-session-one">
                <a class="btn btn-link" href="https://seattleconsultingmm.com">
                    {{ __('Seattle Consulting Myanmar') }}
                </a>
            </section>
            <section class="footer-session-two">
                <label>Copyright &copy; Seattle Consulting Myanmar Co., Ltd. All rights reserved.</label>
            </section>
        </footer>
    </div>
    <script>
        $(document).ready(function() {
            // Smooth fade-in effect when the page loads
            $("body").hide().fadeToggle(600);
            
        });
    </script>
</body>
</html>
