<nav class="navbar navbar-expand-lg bg-dark py-3" data-bs-theme="dark" style="border-bottom: 1px solid #3a4241;">
    <div class="container">
        <a class="navbar-brand fw-bold" style="color: #5c8473;">📚 Notes Manager</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center"> 
                
                {{-- AUTHENTICATED USER NAVIGATION --}}
                @auth
                    <li class="nav-item">
                        <a class="btn btn-sm fw-semibold px-3 text-decoration-none {{ Request::is('dashboard') ? 'text-white' : 'text-secondary' }}" 
                           href="{{ route('dashboard') }}" 
                           style="{{ Request::is('dashboard') ? 'background-color: #4a6b5d;' : 'background-color: transparent;' }} border: none;">
                            Dashboard
                        </a>
                    </li>
                                        
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-sm fw-semibold px-3 text-decoration-none {{ Request::is('profile*') ? 'text-white' : 'text-secondary' }}" 
                           href="{{ route('profile') }}" 
                           style="{{ Request::is('profile*') ? 'background-color: #4a6b5d;' : 'background-color: transparent;' }} border: none;">
                            Profile
                        </a>
                    </li>
                @endauth

                {{-- GUEST NAVIGATION --}}
                @guest
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ Request::is('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-sm fw-semibold px-3 text-white" href="{{ route('register') }}" style="background-color: #4a6b5d;">Register</a>
                    </li>
                @endguest

            </ul>
        </div>
    </div>
</nav>