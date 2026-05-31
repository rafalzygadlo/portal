<?php

namespace Database\Seeders;

use App\Models\Offer\Offer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\Alignment;
use Intervention\Image\Typography\FontFactory;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $manager = new ImageManager(new Driver());

        $users = \App\Models\User::all();
        if ($users->isEmpty()) {
            $users = \App\Models\User::factory()->count(10)->create();
        }

        $categories = \App\Models\Category::all();
        Offer::factory()
            ->count(100)
            ->create(function () use ($users) {
                // Define random creation date and a subsequent update date
                $createdAt = fake()->dateTimeBetween('-2 months', 'now');
                $title = fake()->sentence(5);

                return [
                    'user_id' => $users->random()->id,
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'created_at' => $createdAt,
                    'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
                ];
            })
            ->each(function (Offer $offer) use ($categories, $manager) {
                // Attach categories after the model is created and saved
                if ($categories->isNotEmpty()) {
                    $numberOfCategories = rand(1, min(3, $categories->count()));
                    $randomCategories = $categories->random($numberOfCategories);
                    $offer->categories()->attach($randomCategories->pluck('id')->toArray());
                }

                // 2. Generowanie obrazków z Canvasem
                $offerId = $offer->id;
                $dir = "offers/{$offerId}";
                $thumbDir = "{$dir}/thumbnails";                                
                
                for ($i = 1; $i <= rand(1, 10); $i++) 
                {
                    $filename = "auto-{$i}.jpg";

                    // Generujemy losowy kolor tła
                    $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

                    
                    $img = $manager->createImage(800, 600); 
                    $img->fill($color);

                    // Tworzymy "Canvas" (obraz główny)
                    $img->text('Oferta: ' . $offer->title, 400, 300, function (FontFactory $font) {
                        $font->size(20);
                        $font->color('#ffffff');
                        $font->align(Alignment::CENTER, Alignment::TOP);
                        
                    });

                    // Zapis oryginału
                    Storage::disk('public')->put("{$dir}/{$filename}", (string) $img->encodeUsingFormat(FORMAT::JPEG, quality: 80));

                    // Tworzymy miniaturkę (Canvas - resize)
                    $thumb = clone $img; // Klonujemy, żeby nie zepsuć oryginału
                    $thumb->resize(200, 200);

                    // Zapis miniatury
                    Storage::disk('public')->put("{$thumbDir}/{$filename}", (string) $thumb->encodeUsingFormat(FORMAT::JPEG, quality: 80));

                    // Zapis do bazy
                    $offer->images()->create(['path' => "{$dir}/{$filename}"]);
                }


            });
    }
}
