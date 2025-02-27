<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bulletinboard') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    @vite(['resources/js/app.js'])
    @vite(['resources/css/app.css'])
    @vite(['resources/css/post/upload.css'])
</head>

<body>
    <div id="app">
        <div class="bg-header">
            <header class="container-nav">
                <div class="mdle header">

                    <a class="navbar-brand" href="{{ url('/') }}">
                       Bulletinboard
                    </a>
                    <nav>
                        <div class="hamburger">
                            <span class="bar"></span>
                            <span class="bar"></span>
                            <span class="bar"></span>
                        </div>
                        <div class="nav-link">
                            <a href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> Users
                            </a>
                            <a href="{{ url('/posts') }}">
                                <i class="bi bi-card-list"></i> Posts
                            </a>

                            @if (Auth::check() && Auth::user()->type == 0)
                                <a href="{{ route('users.show') }}">
                                    <i class="bi bi-plus-circle"></i> Create User
                                </a>
                            @endif

                            @guest
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}">
                                        <i class="bi bi-person-circle"></i> {{ __('Login') }}
                                    </a>
                                @endif
                            @else
                                <a id="navbarDropdown"
                                    class="dropdown-toggle d-flex align-items-center justify-content-center" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                                    <i class="bi bi-gear ms-2"></i>
                                </a>

                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#profileModal">
                                        <i class="bi bi-person"></i> {{ __('Profile') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            @endguest
                        </div>

                    </nav>
                    <div class="layer-window"></div>
                </div>
            </header>
        </div>
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
                        <div class="modal-header text-white" style="background-color: #40a3a7">
                            <h5 class="modal-title" id="profileModalLabel">
                                <i class="bi bi-person-circle me-2"></i> Profile
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Profile Image -->
                            <div class="text-center mb-4">
                                <img src="{{ Auth::user()->profile ? asset('storage/' . Auth::user()->profile) : asset('images/default-profile.png') }}"
                                    alt="Profile Picture" class="rounded-circle border border-3 border-success"
                                    width="120" height="120">
                            </div>

                            <!-- Profile Details -->
                            <div class="card-one shadow-sm p-4">
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
                <a class="btn btn-link text-sz" href="https://seattleconsultingmm.com">
                    {{ __('Seattle Consulting Myanmar') }}
                </a>
            </section>
            <section class="footer-session-two">
                <label class="text-sz">Copyright &copy; Seattle Consulting Myanmar Co., Ltd. All rights reserved.</label>
            </section>
        </footer>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $(document).ready(function() {
                $("body").hide().fadeToggle(600);
                const hamburger = $(".hamburger");
                const navlink = $(".nav-link");
                const layerWindow = $(".layer-window");

                hamburger.on("click", function() {
                    hamburger.toggleClass("active");
                    if (hamburger.hasClass("active")) {
                        layerWindow.css("display", "block");
                        navlink.css("height", "450px");
                    } else {
                        layerWindow.css("display", "none");
                        navlink.css("height", "0px");
                    }
                });

                layerWindow.on("click", function() {
                    hamburger.removeClass("active");
                    layerWindow.css("display", "none");
                    navlink.css("height", "0px");
                });

            });
        });
    </script>
</body>

</html>
