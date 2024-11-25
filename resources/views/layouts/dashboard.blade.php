<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            color: #212529;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensures the body takes the full viewport height */
        }

        /* Header with Animated Gradient */
        header {
            background: linear-gradient(90deg, #4caf50, #2196f3, #e91e63);
            background-size: 300% 300%; /* Increase size for flowing effect */
            animation: gradient-flow 6s ease infinite; /* Apply animation */
            color: #fff;
            padding: 15px 0;
        }

        header h1 {
            font-weight: bold;
            font-size: 1.5rem;
        }

        header form button {
            background-color: #f8f9fa;
            color: #007bff;
            border: none;
            font-weight: bold;
        }

        header form button:hover {
            background-color: #e2e6ea;
        }

        /* Navbar with Animated Gradient */
          /* Navbar with Animated Gradient */
        .custom-nav {
            background: linear-gradient(90deg, #4caf50, #2196f3, #e91e63); /* Replace yellow with reddish */
            background-size: 300% 300%; /* Increase size for flowing effect */
            animation: gradient-flow 6s ease infinite; /* Apply animation */
            padding: 15px 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .custom-nav .btn {
            border-radius: 50px;
            transition: transform 0.2s ease-in-out;
        }

        .custom-nav .btn:hover {
            background-color: #fff;
            transform: scale(1.05);
        }

        .custom-nav .btn:active {
            transform: scale(0.95);
        }

        /* Main Content */
        main.container {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        /* Footer */
        footer {
            background-color: #343a40;
            color: #f8f9fa;
            padding: 15px 0;
            font-size: 0.875rem;
            border-top: 4px solid #4caf50;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 0;
        }
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }

        .pagination .page-item {
            margin: 0 0.25rem;
        }

        .pagination .page-item .page-link {
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            border-radius: 50px;
            transition: all 0.2s ease-in-out;
        }

        .pagination .page-item .page-link:hover {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }


        /* Gradient Animation */
        @keyframes gradient-flow {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Additional Styles */
        a, a:hover {
            text-decoration: none;
        }

        .btn {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="mb-0">SEIDR Survey Dashboard</h1>
            @if(Auth::check())
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm fw-bold text-primary">Logout</button>
                </form>
            @endif
        </div>
    </header>

    <!-- Navigation Bar -->
    <!-- Navigation Bar -->
    <div class="custom-nav">
        <div class="container d-flex justify-content-start gap-3">
            <a href="{{ route('responses.index') }}" class="btn btn-light btn-lg fw-bold text-primary">Home</a>
            <a href="{{ route('responses.index') }}" class="btn btn-light btn-lg fw-bold text-primary">Responses</a>
            <a href="{{ route('responses.analytics') }}" class="btn btn-light btn-lg fw-bold text-primary">Analytics</a>
        </div>
    </div>

    <!-- Main Content Section -->
    <main class="container">
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer>
        <p>&copy; {{ date('Y') }} SEIDR Dynamics. All rights reserved.</p>
    </footer>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
