<?php

namespace Database\Seeders;

use App\Models\Offer;
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

        $offersCount = 1000;

        $manager = new ImageManager(new Driver());

        $users = \App\Models\User::all();
        if ($users->isEmpty()) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }

        $categories = \App\Models\Category::all();

        $this->command->info('Generating offers with images...');
        $this->command->getOutput()->progressStart($offersCount);

        $reasons = ['spam', 'reported', 'expired', 'admin_deleted'];

        for ($i = 0; $i < $offersCount; $i++) 
        {
            $createdAt = fake()->dateTimeBetween('-2 months', 'now');
            $isDeleted = fake()->boolean(15); // 15% szans na soft delete

            $offer = Offer::factory()->create([
                'user_id' => $users->random()->id,
                'created_at' => $createdAt,
                'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
                'deleted_at' => $isDeleted ? now() : null,
                'deletion_reason' => $isDeleted ? fake()->randomElement($reasons) : null,
            ]);

            // Obsługa kategorii
            if ($categories->isNotEmpty()) {
                // Attach categories after the model is created and saved
                $numberOfCategories = rand(1, min(3, $categories->count()));
                $randomCategories = $categories->random($numberOfCategories);
                $offer->categories()->attach($randomCategories->pluck('id')->toArray());
            }

            // Generowanie obrazów przez funkcję
            $this->generateOfferImages($offer, $manager);

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }

    /**
     * Generates and saves images for a given offer.
     */
    private function generateOfferImages(Offer $offer, ImageManager $manager): void
    {
        $offerId = $offer->id;
        $dir = "offers/{$offerId}";
        $thumbDir = "{$dir}/thumbnails";

        for ($i = 1; $i <= rand(1, 5); $i++) {
            $filename = "auto-{$i}.jpg";
            $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));

            $img = $manager->createImage(800, 600);
            $img->fill($color);

            $img->text('Oferta: ' . $offer->title, 400, 300, function (FontFactory $font) {
                $font->size(20);
                $font->color('#ffffff');
                $font->align(Alignment::CENTER, Alignment::TOP);
            });

            // Zapis oryginału
            Storage::disk('public')->put("{$dir}/{$filename}", (string) $img->encodeUsingFormat(FORMAT::JPEG, quality: 80));

            // Tworzymy miniaturkę
            $thumb = clone $img;
            $thumb->resize(200, 200);

            // Zapis miniatury
            Storage::disk('public')->put("{$thumbDir}/{$filename}", (string) $thumb->encodeUsingFormat(FORMAT::JPEG, quality: 80));

            // Zapis do bazy
            $offer->images()->create(['path' => "{$dir}/{$filename}"]);
        }
    }
}

           