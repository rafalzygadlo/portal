<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\Typography\FontFactory;
use Intervention\Image\Alignment;
use Intervention\Image\Geometry\Factories\RectangleFactory;



class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $offersCount = 100;

        $manager = new ImageManager(new Driver());

        $users = \App\Models\User::all();

        if ($users->isEmpty()) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }

        $categories = \App\Models\Category::all();

        $this->command->info('Generating offers with images...');
        $this->command->getOutput()->progressStart($offersCount);

        $reasons = [
            'spam',
            'reported',
            'expired',
            'admin_deleted',
        ];

        for ($i = 0; $i < $offersCount; $i++) {
            $createdAt = fake()->dateTimeBetween('-2 months', 'now');
            $isDeleted = fake()->boolean(15);

            $offer = Offer::factory()->create([
                'user_id' => $users->random()->id,
                'created_at' => $createdAt,
                'updated_at' => fake()->dateTimeBetween($createdAt, 'now'),
                'deleted_at' => $isDeleted ? now() : null,
                'deletion_reason' => $isDeleted
                    ? fake()->randomElement($reasons)
                    : null,
            ]);

            // Kategorie
            if ($categories->isNotEmpty()) {
                $numberOfCategories = rand(
                    1,
                    min(3, $categories->count())
                );

                $randomCategories = $categories->random($numberOfCategories);

                $offer->categories()->attach(
                    $randomCategories->pluck('id')->toArray()
                );
            }

            // Obrazki
            $this->generateOfferImages($offer, $manager);

            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }

    /**
     * Generuje obrazki dla oferty.
     */
    private function generateOfferImages(
        Offer $offer,
        ImageManager $manager
    ): void {
        $offerId = $offer->id;

        $dir = "offers/{$offerId}";
        $thumbDir = "{$dir}/thumb";

        $imagesCount = rand(1, 5);

        for ($i = 1; $i <= $imagesCount; $i++) {
            $filename = "auto-{$i}.jpg";

            // Losowe kolorowe tło
            $color = sprintf(
                '#%06X',
                mt_rand(0, 0xFFFFFF)
            );

            $img = $manager->createImage(800, 600);

            $img->fill($color);

            // Ciemny półprzezroczysty pasek na dole



            $img->drawRectangle(
                function (RectangleFactory $rectangle) {
                    $rectangle->width(800);
                    $rectangle->height(180);
                    $rectangle->background('rgba(0, 0, 0, 0.65)');
                }
            );



            // Tytuł
            $title = \Illuminate\Support\Str::limit(
                $offer->title,
                45
            );

            $img->text(
                $title,
                40,
                465,
                function (FontFactory $font) {
                  

                    $font->size(138);
                    $font->color('#ffffff');

                    $font->align(
                        Alignment::LEFT,
                        Alignment::TOP
                    );
                }
            );

            // Mały napis
            $img->text(
                'Oferta',
                40,
                545,
                function (FontFactory $font) {
                  

                    $font->size(122);
                    $font->color('#dddddd');

                    $font->align(
                        Alignment::LEFT,
                        Alignment::TOP
                    );
                }
            );

            // Oryginał
            Storage::disk('public')->put(
                "{$dir}/{$filename}",
                (string) $img->encodeUsingFormat(
                    Format::JPEG,
                    quality: 80
                )
            );

            // Miniatura
            $thumb = clone $img;

            $thumb->resize(200, 200);

            Storage::disk('public')->put(
                "{$thumbDir}/{$filename}",
                (string) $thumb->encodeUsingFormat(
                    Format::JPEG,
                    quality: 80
                )
            );

            // Zapis obrazka do bazy
            $offer->images()->create([
                'path' => "{$dir}/{$filename}",
            ]);
        }
    }
}
