<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Survey App')</title>
    <!-- Include CSS -->


    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            color: #fff;
            padding: 15px 0;
        }

        footer {
            background: #f1f1f1;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }

        input{
  background-color: none;
  border: none;
  border-bottom: 1px solid black;
  background: transparent;
}
    </style>
    <link href="{{ asset('css/survey.css') }}" rel="stylesheet">
</head>
<body>
    <header>
        <div class="d-flex justify-content-between align-items-center">


            @if(Auth::check())
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            @endif
        </div>
    </header>

    <main class="container mt-4">
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} SEIDR Dynamics. All rights reserved.</p>
    </footer>
    @yield('scripts')
</body>
</html>
