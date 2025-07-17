<!DOCTYPE html>
<html>
<head>
    <title>My E-Comm App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .main-content { min-height: 100vh; }
        @media (max-width: 768px) {
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    @auth
        <div class="d-flex">
            @include('components.sidebar')
            <div class="main-content flex-grow-1 p-4">
                @yield('content')
            </div>
        </div>
    @else
        @yield('content')
    @endauth
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>