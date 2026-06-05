<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #161a19; /* Deep forest black background */
            color: #e4e6e5; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }

        /* Register Wrapper Layout */
        .register-wrapper {
            min-height: 100vh; /* Inayos mula sa min-vh-100 para iwas layout bug */
            display: flex;
            align-items: center;
            padding-top: 60px;
            padding-bottom: 80px;
        }

        /* Modern Custom Card (Glassmorphism Effect) */
        .register-card { 
            background: linear-gradient(145deg, #222928, #1c2120);
            border: 1px solid rgba(92, 132, 115, 0.15); 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .register-card:hover {
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

        @yield('content')
        

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>