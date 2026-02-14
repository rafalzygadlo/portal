<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use App\Models\Category;
use App\Models\Reservation;
use App\Models\ReservationService;
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
                'user_id' => $owner->id,
                'name' => $businessData['name'],
                'slug' => Str::slug($businessData['name']),
                'subdomain' => Str::slug($businessData['name']),
                'description' => 'Profesjonalny biznes o wysokim standardzie usług.',
                'address' => fake()->address(),
                'phone' => fake()->phoneNumber(),
                'website' => 'https://' . Str::slug($businessData['name']) . '.localhost',
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
                'booking_slot_duration' => 30
            ]);

            if ($businessCategories->count() > 0) {
                $numberOfCategories = rand(1, min(3, $businessCategories->count()));
                $randomCategories = $businessCategories->random($numberOfCategories);
                $business->categories()->attach($randomCategories->pluck('id')->toArray());
            }

            // Uaktualnij owner's current_business_id
            //$owner->update(['current_business_id' => $business->id, 'user_type' => 'business_owner']);

            // Dodaj pracowników
            $employees = User::factory(3)->create();

            $employeeIds = $employees->pluck('id')->toArray();

            foreach ($employees as $employee) {
                $business->employees()->attach($employee->id, [
                    'role' => fake()->randomElement(['employee', 'manager']),
                    'is_active' => true,
                ]);
            }

            // Utwórz usługi rezerwacji
            $services = [];
            foreach ($businessData['services'] as $serviceName) {
                $service = ReservationService::create([
                    'business_id' => $business->id,
                    'name' => $serviceName,
                    'description' => 'Profesjonalna usługa: ' . $serviceName,
                    'price' => fake()->randomElement([50, 75, 100, 150, 200]),
                    'duration_minutes' => fake()->randomElement([30, 45, 60, 90]),
                    'buffer_minutes' => 15,
                    'is_active' => true,
                ]);
                $services[] = $service;
            }

            // Utwórz rezerwacje dla danego biznesu
            for ($i = 0; $i < 8; $i++) {
                $service = fake()->randomElement($services);
                $startTime = now()->addDays(fake()->numberBetween(1, 30))
                    ->setHour(fake()->numberBetween(9, 17))
                    ->setMinute(0);

                $userIdOptions = array_merge($employeeIds, [null]);

                Reservation::create([
                    'business_id' => $business->id,
                    'reservation_service_id' => $service->id,
                    'user_id' => fake()->randomElement($userIdOptions),
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



