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
            'Motoryzacja' => [
                'Samochody osobowe',
                'Samochody dostawcze',
                'Motocykle',
                'Części samochodowe',
                'Części motocyklowe',
                'Opony i felgi',
                'Akcesoria samochodowe',
                'Narzędzia i wyposażenie warsztatu',
                'Przyczepy',
            ],

            'Dom i Ogród' => [
                'Meble' => [
                    'Sofy i kanapy',
                    'Fotele',
                    'Stoły i krzesła',
                    'Szafy',
                    'Komody',
                    'Łóżka i materace',
                ],
                'Wyposażenie domu' => [
                    'Dekoracje',
                    'Oświetlenie',
                    'Dywany i chodniki',
                    'Naczynia i sztućce',
                ],
                'Ogród' => [
                    'Narzędzia ogrodowe',
                    'Kosiarki',
                    'Meble ogrodowe',
                    'Rośliny',
                    'Grille',
                ],
                'Materiały budowlane',
                'Drzwi i okna',
                'Ogrzewanie',
            ],

            'Elektronika' => [
                'Komputery' => [
                    'Laptopy',
                    'Komputery stacjonarne',
                    'Monitory',
                    'Podzespoły komputerowe',
                    'Drukarki',
                    'Akcesoria komputerowe',
                ],
                'Telefony' => [
                    'Smartfony',
                    'Telefony komórkowe',
                    'Akcesoria do telefonów',
                ],
                'Telewizory i RTV' => [
                    'Telewizory',
                    'Audio',
                    'Sprzęt RTV',
                ],
                'Fotografia' => [
                    'Aparaty',
                    'Obiektywy',
                    'Akcesoria fotograficzne',
                ],
                'Konsole i gry',
            ],

            'Praca' => [
                'Oferty pracy',
                'Praca dodatkowa',
                'Praca zdalna',
                'Praca sezonowa',
                'Praktyki i staże',
                'Usługi pracy',
            ],

            'Nieruchomości' => [
                'Mieszkania na sprzedaż',
                'Mieszkania na wynajem',
                'Domy na sprzedaż',
                'Domy na wynajem',
                'Działki',
                'Lokale użytkowe',
                'Garaże i miejsca parkingowe',
                'Nieruchomości za granicą',
            ],

            'Usługi' => [
                'Budownictwo i remonty',
                'Transport i przeprowadzki',
                'Motoryzacyjne',
                'IT i programowanie',
                'Fotografia i wideo',
                'Usługi dla firm',
                'Sprzątanie',
                'Ogrodnictwo',
                'Korepetycje i edukacja',
                'Opieka',
                'Zdrowie i uroda',
                'Usługi finansowe',
                'Inne usługi',
            ],

            'Moda' => [
                'Odzież damska',
                'Odzież męska',
                'Odzież dziecięca',
                'Obuwie',
                'Torebki i plecaki',
                'Biżuteria i zegarki',
                'Akcesoria',
            ],

            'Dla dzieci' => [
                'Zabawki',
                'Wózki dziecięce',
                'Foteliki samochodowe',
                'Ubrania dziecięce',
                'Meble dziecięce',
                'Akcesoria dla dzieci',
            ],

            'Sport i Hobby' => [
                'Rowery',
                'Sport i fitness',
                'Turystyka',
                'Wędkarstwo',
                'Kolekcjonerstwo',
                'Instrumenty muzyczne',
                'Książki',
                'Gry planszowe',
            ],

            'Rolnictwo' => [
                'Maszyny rolnicze',
                'Części rolnicze',
                'Narzędzia i sprzęt',
                'Zwierzęta gospodarskie',
                'Płody rolne',
                'Materiały rolnicze',
            ],

            'Zwierzęta' => [
                'Psy',
                'Koty',
                'Ptaki',
                'Ryby',
                'Akcesoria dla zwierząt',
                'Usługi dla zwierząt',
            ],

            'Przemysł i Firmy' => [
                'Maszyny i urządzenia',
                'Narzędzia',
                'Wyposażenie firm',
                'Wyposażenie sklepów',
                'Wyposażenie gastronomii',
                'Sprzęt biurowy',
            ],

            'Pozostałe' => [
                'Oddam za darmo',
                'Zamienię',
                'Znaleziono',
                'Zgubiono',
                'Różne',
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