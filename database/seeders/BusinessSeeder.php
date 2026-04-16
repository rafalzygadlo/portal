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
            ['name' => 'Warsaw Tire Service', 'services' => ['Tire replacement', 'Tire repair', 'Wheel balancing', 'Tire storage']],
            ['name' => 'Salon Fryzjerski Luna', 'services' => ['Haircut', 'Coloring', 'Care treatments']],
            ['name' => 'Dental Clinic', 'services' => ['Cleaning', 'Root canal treatment', 'Implants', 'Orthodontics']],
            ['name' => 'Studio Zen Massage', 'services' => ['Relaxing massage', 'Sports massage', 'Facial massage']],
            ['name' => 'Pro Auto Mechanics', 'services' => ['Inspection', 'Oil change', 'Engine repair', 'Diagnostics']],
            ['name' => 'Beauty Studio Glamour', 'services' => ['Makeup', 'Pedicure', 'Manicure', 'Brows']],
            ['name' => 'Fitness Centrum Mocy', 'services' => ['Trening personalny', 'Joga', 'Pilates', 'Grupy fitnessu']],
            ['name' => 'Tailoring Studio', 'services' => ['Custom tailoring', 'Alterations', 'Repairs']],
            ['name' => 'Nauka Jazdy Pro', 'services' => ['Prawo jazdy kat. B', 'Prawo jazdy kat. A', 'Kurs odnowienia']],
            ['name' => 'Photography Studio', 'services' => ['Portrait session', 'Event session', 'Product photography']],
            ['name' => 'Massage Studio', 'services' => ['Portrait session', 'Event session', 'Product photography']],
        ];

        $businessCategories = Category::where('type', 'business')->get();

        // Create business owners
        $owners = User::factory(11)->create();

        foreach ($businessTypes as $index => $businessData) {
            $owner = $owners[$index];

            $business = Business::create([
                'name' => $businessData['name'],
                'subdomain' => Str::slug($businessData['name']),
                'description' => 'A professional business with a high standard of services.',
                'address' => fake()->address(),
                'phone' => fake()->phoneNumber(),
                //'website' => 'https://' . Str::slug($businessData['name']) . config('app.business_domain'),
                //'latitude' => fake()->latitude(51.5, 52.5),
                //'longitude' => fake()->longitude(20.5, 21.5),
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

            // Assign owner to business
            $business->users()->attach($owner->id, ['owner' => true]);

            // Update owner's current_business_id
            //$owner->update(['current_business_id' => $business->id, 'user_type' => 'business_owner']);

            // Add resources (e.g., staff, equipment)
            $resources = Resource::factory(3)->create([
                'business_id' => $business->id,
                'type' => fake()->randomElement(['person', 'equipment'])
            ]);

            // Create services for business
            $createdServices = collect();
            foreach ($businessData['services'] as $serviceName) {
                $service = Service::create([
                    'business_id' => $business->id,
                    'name' => $serviceName,
                    'description' => 'Professional service: ' . $serviceName,
                    'price' => fake()->randomElement([50, 75, 100, 150, 200]),
                    'duration' => fake()->randomElement([30, 45, 60, 90]),
                    'buffer' => 15,
                    'is_active' => true,
                ]);
                $createdServices->push($service);
            }

            // Assign services to resources (many-to-many relation)
            if ($createdServices->isNotEmpty()) {
                foreach ($resources as $resource) {
                    $servicesToAttach = $createdServices->random(rand(1, $createdServices->count()))->pluck('id');
                    $resource->services()->attach($servicesToAttach);
                }
            }

            // Create reservations for the business
            for ($i = 0; $i < 8; $i++) {
                if ($createdServices->isEmpty()) continue;

                $service = $createdServices->random();
                $startTime = now()->addDays(fake()->numberBetween(1, 30))
                    ->setHour(fake()->numberBetween(9, 17))
                    ->setMinute(0);

                Reservation::create([
                    'business_id' => $business->id,
                    'service_id' => $service->id,
                    'user_id' => null, // set to null; can be replaced with the authenticated client ID
                    'client_name' => fake()->name(),
                    'client_email' => fake()->email(),
                    'client_phone' => fake()->phoneNumber(),
                    'start_time' => $startTime,
                    'end_time' => $startTime->copy()->addMinutes($service->duration),
                    'notes' => fake()->randomElement([null, 'Special requests', 'First visit']),
                    'status' => fake()->randomElement(['pending', 'confirmed', 'completed']),
                ]);
            }
        }
    }
}



