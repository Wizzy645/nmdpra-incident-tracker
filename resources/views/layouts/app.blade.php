<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NMDPRA Incident Tracker')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-900 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">NMDPRA Incident Tracker</h1>
            @auth
            <div class="flex items-center gap-4">
                <span>{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="bg-red-600 px-3 py-1 rounded hover:bg-red-700">Logout</button></form>
            </div>
            @endauth
        </div>
    </nav>
    <main class="container mx-auto p-6">
        @if(session('success'))<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>@endif
        @yield('content')
    </main>
</body>
</html>
