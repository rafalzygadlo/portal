<div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div>
                <a href="/" class="text-gray-600 hover:text-gray-900 font-medium">← Wróć na stronę</a>
                <h1 class="text-xl font-bold mt-1">{{ $business->name }}</h1>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto px-4 py-8">
        @livewire('booking-widget', ['business' => $business])
    </div>
</div>
