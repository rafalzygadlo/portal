<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use App\Models\Category;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $businessTypes = [
            ['name' => 'Serwis Opon Warszawa', 'services' => ['Wymiana opon', 'Naprawa opony', 'Wyważanie', 'Przechowywanie']],
            ['name' => 'Salon Fryzjerski Luna', 'services' => ['Strzyżenie', 'Koloryzacja', 'Zabiegi pielęgnacyjne']],
            ['name' => 'Klinika Stomatologiczna', 'services' => ['Czyszczenie', 'Leczenie kanałowe', 'Implanty', 'Ortodoncja']],
            ['name' => 'Studio Masażu Zen', 'services' => ['Masaż relaksacyjny', 'Masaż sportowy', 'Masaż twarzy']],
            ['name' => 'Mechanika Samochodowa Pro', 'services' => ['Przegląd', 'Wymiana olejów', 'Naprawa silnika', 'Diagnostyka']],
            ['name' => 'Beauty Studio Glamour', 'services' => ['Makijaż', 'Pedicure', 'Manicure', 'Brwi']],
            ['name' => 'Fitness Centrum Mocy', 'services' => ['Trening personalny', 'Joga', 'Pilates', 'Grupy fitnessu']],
            ['name' => 'Atelier Odzieżowe', 'services' => ['Szycie na miarę', 'Alteracje', 'Naprawy']],
            ['name' => 'Nauka Jazdy Pro', 'services' => ['Prawo jazdy kat. B', 'Prawo jazdy kat. A', 'Kurs odnowienia']],
            ['name' => 'Studio Fotografii', 'services' => ['Sesja portretowa', 'Sesja eventowa', 'Fotografia produktów']],
            ['name' => 'Studio Masazu', 'services' => ['Sesja portretowa', 'Sesja eventowa', 'Fotografia produktów']],
        ];

        $businessCategories = Category::where('type', 'business')->get();

        // Utworz właścicieli biznesów
        $owners = User::factory(11)->create();

        foreach ($businessTypes as $index => $businessData) {
            $owner = $owners[$index];

            $business = Business::create([
                'name' => $businessData['name'],
                'subdomain' => Str::slug($businessData['name']),
                'description' => 'Profesjonalny biznes o wysokim standardzie usług.',
                'address' => fake()->address(),
                'phone' => fake()->phoneNumber(),
                'website' => 'https://' . Str::slug($businessData['name']) . env('DOMAIN_NAME'),
                'latitude' => fake()->latitude(51.5, 52.5),
                'longitude' => fake()->longitude(20.5, 21.5),
                'business_hours' => [
                    'mon' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'tue' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'wed' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'thu' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'fri' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'sat' => ['open' => '10:00', 'close' => '16:00', 'closed' => false],
                    'sun' => ['closed' => true],
                ],
                
            ]);

            if ($businessCategories->count() > 0) {
                $numberOfCategories = rand(1, min(3, $businessCategories->count()));
                $randomCategories = $businessCategories->random($numberOfCategories);
                $business->categories()->attach($randomCategories->pluck('id')->toArray());
            }

            // Przypisz właściciela do biznesu
            $business->users()->attach($owner->id, ['owner' => true]);

            // Uaktualnij owner's current_business_id
            //$owner->update(['current_business_id' => $business->id, 'user_type' => 'business_owner']);

            // Dodaj zasoby (np. pracowników, sprzęt)
            $resources = Resource::factory(3)->create([
                'business_id' => $business->id,
                'type' => fake()->randomElement(['person', 'equipment'])
            ]);

            // Utwórz usługi dla biznesu
            $createdServices = collect();
            foreach ($businessData['services'] as $serviceName) {
                $service = Service::create([
                    'business_id' => $business->id,
                    'name' => $serviceName,
                    'description' => 'Profesjonalna usługa: ' . $serviceName,
                    'price' => fake()->randomElement([50, 75, 100, 150, 200]),
                    'duration_minutes' => fake()->randomElement([30, 45, 60, 90]),
                    'buffer_minutes' => 15,
                    'is_active' => true,
                ]);
                $createdServices->push($service);
            }

            // Przypisz usługi do zasobów (relacja Many-to-Many)
            if ($createdServices->isNotEmpty()) {
                foreach ($resources as $resource) {
                    $servicesToAttach = $createdServices->random(rand(1, $createdServices->count()))->pluck('id');
                    $resource->services()->attach($servicesToAttach);
                }
            }

            // Utwórz rezerwacje dla danego biznesu
            for ($i = 0; $i < 8; $i++) {
                if ($createdServices->isEmpty()) continue;

                $service = $createdServices->random();
                $startTime = now()->addDays(fake()->numberBetween(1, 30))
                    ->setHour(fake()->numberBetween(9, 17))
                    ->setMinute(0);

                Reservation::create([
                    'business_id' => $business->id,
                    'service_id' => $service->id,
                    'user_id' => null, // Ustawiamy na null, można tu wstawić ID zalogowanego klienta
                    'client_name' => fake()->name(),
                    'client_email' => fake()->email(),
                    'client_phone' => fake()->phoneNumber(),
                    'start_time' => $startTime,
                    'end_time' => $startTime->copy()->addMinutes($service->duration_minutes),
                    'notes' => fake()->randomElement([null, 'Specjalne życzenia', 'Pierwsza wizyta']),
                    'status' => fake()->randomElement(['pending', 'confirmed', 'completed']),
                ]);
            }
        }
    }
}



