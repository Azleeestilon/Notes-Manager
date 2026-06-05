@extends('layouts.main')

@section('content')
    <div class="register-wrapper d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-4"> 
                    
                    <div class="card register-card p-4">
                        <div class="card-body">
                            
                            <div class="text-center mb-4">
                                <div class="d-inline-block p-3 rounded-circle mb-2" style="background-color: rgba(92, 132, 115, 0.1);">
                                    <span style="font-size: 2rem;">📝</span>
                                </div>
                                <h3 class="fw-bold mb-1">Create Account</h3>
                                <p class="text-muted small">Join the workspace platform</p>
                            </div>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger bg-danger text-white border-0 py-2 rounded-3 mb-4">
                                    <ul class="mb-0 small ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register.post') }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label-custom">FULL NAME</label>
                                    <input type="text" name="name" class="form-control form-control-custom" placeholder="John Doe" value="{{ old('name') }}" required autofocus>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-custom">EMAIL ADDRESS</label>
                                    <input type="email" name="email" class="form-control form-control-custom" placeholder="name@example.com" value="{{ old('email') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-custom">PASSWORD</label>
                                    <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label-custom">CONFIRM PASSWORD</label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-custom" placeholder="••••••••" required>
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-moss-premium">Get Started</button>
                                </div>

                                <div class="text-center small mt-4 pt-2">
                                    <span style="color: #8fa099;">Already have an account?</span> 
                                    <a href="{{ route('login') }}" class="link-moss ms-1">Sign In instead</a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection