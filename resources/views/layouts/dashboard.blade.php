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
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
        }

        footer {
            background: #343a40;
            color: #fff;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }

        .container {
            margin-top: 30px;
        }

        nav .nav-link {
            color: #fff;
        }

        nav .nav-link:hover {
            color: #f8f9fa;
        }
    </style>
</head>
<body>
    <header>
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Dashboard</h1>
            @if(Auth::check())
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm">Logout</button>
                </form>
            @endif
        </div>
    </header>

    <nav class="bg-dark p-2">
        <div class="container">
            <a class="nav-link d-inline" href="{{ route('responses.index') }}">Home</a>
            <a class="nav-link d-inline" href="{{ route('responses.index') }}">Responses</a>
            <a class="nav-link d-inline" href="{{ route('responses.analytics') }}">Analytics</a>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} SEIDR Dynamics. All rights reserved.</p>
    </footer>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
