<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarezerwuj - {{ $business->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div>
                <a href="/" class="text-gray-600 hover:text-gray-900">← Wróć</a>
                <h1 class="text-xl font-bold">{{ $business->name }}</h1>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-8">
        @livewire('booking-widget', ['business' => $business])
    </div>

    @livewireScripts
</body>
</html>
