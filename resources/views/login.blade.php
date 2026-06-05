<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Notes Manager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            background-color: #161a19;
            color: #e4e6e5; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }

        /* Login Wrapper Layout */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 80px;
        }

        /* Modern Custom Card */
        .login-card { 
            background: linear-gradient(145deg, #222928, #1c2120);
            border: 1px solid rgba(92, 132, 115, 0.15); 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(74, 107, 93, 0.15);
        }

        /* Sleek Input Styling */
        .form-label-custom {
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #8fa099;
            margin-bottom: 6px;
        }
        .form-control-custom { 
            background-color: #1a1f1e; 
            border: 1px solid #2d3835; 
            color: #fff; 
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.25s ease;
        }
        .form-control-custom:focus { 
            background-color: #1e2624; 
            border-color: #5c8473; 
            color: #fff; 
            box-shadow: 0 0 0 4px rgba(92, 132, 115, 0.15); 
        }

        /* Premium Moss Button with Glow Effect */
        .btn-moss-premium { 
            background: linear-gradient(135deg, #4a6b5d, #3b564a);
            color: #fff; 
            border: none; 
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(74, 107, 93, 0.3);
            transition: all 0.3s ease;
        }
        .btn-moss-premium:hover { 
            background: linear-gradient(135deg, #5c8473, #4a6b5d);
            color: #fff; 
            box-shadow: 0 6px 20px rgba(92, 132, 115, 0.5);
            transform: scale(1.02);
        }
        .btn-moss-premium:active {
            transform: scale(0.98);
        }

        /* Links styling */
        .link-moss {
            color: #5c8473; 
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        .link-moss:hover {
            color: #86b09c;
            text-decoration: underline;
        }
    </style>
</head>
<body>
        
    @include('navbar')

    <div class="login-wrapper d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-lg-4"> 
                    
                    <div class="card login-card p-4">
                        <div class="card-body">
                            
                            <div class="text-center mb-4">
                                <div class="d-inline-block p-3 rounded-circle mb-2" style="background-color: rgba(92, 132, 115, 0.1);">
                                    <span style="font-size: 2rem;">🔒</span>
                                </div>
                                <h3 class="fw-bold mb-1">Welcome Back</h3>
                                <p class="text-muted small">Enter your workspace credentials</p>
                            </div>
                            
                            // DISPLAY VALIDATION ERRORS
                            @if ($errors->any())
                                <div class="alert alert-danger bg-danger text-white border-0 py-2 rounded-3 mb-4">
                                    <ul class="mb-0 small ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            // LOGIN FORM
                            <form method="POST" action="{{ route('login.post') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label-custom">EMAIL ADDRESS</label>
                                    <input type="email" id="email" name="email" class="form-control form-control-custom" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                                </div>

                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label for="password" class="form-label-custom mb-0">PASSWORD</label>
                                    </div>
                                    <input type="password" id="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-moss-premium">Sign In to Account</button>
                                </div>

                                <div class="text-center small mt-4 pt-2">
                                    <span style="color: #8fa099;">New to the platform?</span> 
                                    <a href="{{ route('register') }}" class="link-moss ms-1">Create an account</a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>