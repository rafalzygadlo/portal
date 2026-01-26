<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel zarządzania - {{ $business->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">{{ $business->name }} - Panel zarządzania</h1>
            <a href="/logout" class="text-gray-600 hover:text-gray-900">Wyloguj się</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        @livewire('business-dashboard', ['business' => $business])
    </div>

    @livewireScripts
</body>
</html>
