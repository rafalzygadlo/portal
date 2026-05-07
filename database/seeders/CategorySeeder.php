<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definicja hierarchiczna kategorii
        $categories = [
            'Elektronika' => [
                'Komputery' => [
                    'Laptopy',
                    'Komputery stacjonarne',
                    'Podzespoły',
                ],
                'Telefony' => [
                    'Smartfony',
                    'Akcesoria do telefonów',
                    'Telefony stacjonarne',
                ],
                'Telewizory i RTV',
            ],
            'Dom i Ogród' => [
                'Meble' => [
                    'Sofy i Kanapy',
                    'Stoły i Krzesła',
                    'Szafy',
                ],
                'Ogród' => [
                    'Narzędzia ogrodowe',
                    'Rośliny',
                ],
            ],
            'Motoryzacja' => [
                'Samochody osobowe',
                'Części samochodowe',
                'Motocykle',
            ],
            'Usługi' => [
                'IT i Programowanie',
                'Budownictwo',
                'Korepetycje',
            ],
        ];

        $this->seedCategories($categories);
    }

    /**
     * Rekurencyjne tworzenie kategorii.
     */
    private function seedCategories(array $categories, ?int $parentId = null): void
    {
        foreach ($categories as $key => $value) {
            // Jeśli klucz jest tekstem, a wartość tablicą -> to jest rodzic
            if (is_array($value)) {
                $category = Category::updateOrCreate(
                    ['slug' => Str::slug($key)],
                    ['name' => $key, 'parent_id' => $parentId]
                );

                $this->seedCategories($value, $category->id);
            } else {
                // Jeśli to tylko wartość tekstowa -> to jest kategoria bez dzieci na tym poziomie
                Category::updateOrCreate(
                    ['slug' => Str::slug($value)],
                    ['name' => $value, 'parent_id' => $parentId]
                );
            }
        }
    }
}