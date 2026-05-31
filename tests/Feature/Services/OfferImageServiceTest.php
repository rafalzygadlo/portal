<?php

namespace Tests\Feature\Services;

use App\Models\Offer\Offer;
use App\Services\OfferImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OfferImageServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_processes_and_saves_images_in_multiple_sizes()
    {
        // 1. Przygotowanie środowiska
        Storage::fake('public');
        
        // Zakładamy, że masz Factory dla modelu Offer
        $offer = Offer::factory()->create([
            'slug' => 'nowoczesny-dom-w-boleslawcu',
            'title' => 'Nowoczesny Dom w Bolesławcu'
        ]);

        $service = new OfferImageService();
        
        // Tworzymy fejkowe zdjęcia do testu
        $photos = [
            UploadedFile::fake()->image('test1.jpg', 2000, 2000),
            UploadedFile::fake()->image('test2.png', 1000, 1000),
        ];

        // 2. Wykonanie usługi
        $service->processAndAttach($offer, $photos);

        // 3. Weryfikacja
        $this->assertCount(2, $offer->images);

        foreach ($offer->images as $image) {
            // Sprawdzenie czy główny plik istnieje (zgodnie ze ścieżką w bazie)
            Storage::disk('public')->assertExists($image->path);
            
            $directory = dirname($image->path);
            $filename = basename($image->path);

            // Sprawdzenie czy miniatury zostały utworzone w odpowiednich podfolderach
            Storage::disk('public')->assertExists("{$directory}/small/{$filename}");
            Storage::disk('public')->assertExists("{$directory}/thumbnails/{$filename}");
        }
    }
}