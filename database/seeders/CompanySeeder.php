<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use App\Models\Category;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companyTypes = [
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

        $companyCategories = Category::all();

        // Create company owners
        $owners = User::factory(11)->create();

        foreach ($companyTypes as $index => $companyData) {
            $owner = $owners[$index];

            $company = Company::create([
                'name' => $companyData['name'],
                'subdomain' => Str::slug($companyData['name']),
                'description' => 'A professional company with a high standard of services.',
                'address' => fake()->address(),
                'phone' => fake()->phoneNumber(),
                'is_claimed' => fake()->boolean(30), // 30% chance of being claimed
                'company_hours' => [
                    'mon' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'tue' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'wed' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'thu' => ['open' => '09:00', 'close' => '18:00', 'closed' => false],
                    'fri' => ['open' => '09:00', 'close' => '19:00', 'closed' => false],
                    'sat' => ['open' => '10:00', 'close' => '16:00', 'closed' => false],
                    'sun' => ['closed' => true],
                ],
            ]);

            if ($companyCategories->count() > 0) {
                $numberOfCategories = rand(1, min(3, $companyCategories->count()));
                $randomCategories = $companyCategories->random($numberOfCategories);
                $company->categories()->attach($randomCategories->pluck('id')->toArray());
            }

            // Assign owner to company
            $company->users()->attach($owner->id, ['owner' => true]);

            // Add resources (e.g., equipment, facilities)
            Resource::factory(3)->create([
                'company_id' => $company->id,
                'type' => fake()->randomElement(['facility', 'equipment']),
            ]);

            // Add 5-10 employees able to perform services
            $employeeCompanyUsers = collect();
            $employeeCount = rand(5, 10);
            foreach (User::factory($employeeCount)->create() as $employee) {
                $company->users()->attach($employee->id, ['owner' => false]);
                $companyUser = CompanyUser::where('company_id', $company->id)->where('user_id', $employee->id)->firstOrFail();

                // Give ~60% of employees a custom display name
                if (fake()->boolean(60)) {
                    $companyUser->update([
                        'display_name' => fake()->randomElement([
                            $employee->first_name . ' the Pro',
                            'Master ' . $employee->first_name,
                            $employee->first_name . ' ' . fake()->randomElement(['Expert', 'Specialist', 'Senior', 'Junior']),
                            fake()->firstName() . ' ' . fake()->lastName(),
                        ]),
                    ]);
                }

                $employeeCompanyUsers->push($companyUser);
            }

            // Create services for company
            $createdServices = collect();
            foreach ($companyData['services'] as $serviceName) {
                $service = Service::create([
                    'company_id' => $company->id,
                    'name' => $serviceName,
                    'description' => 'Professional service: ' . $serviceName,
                    'price' => fake()->randomElement([50, 75, 100, 150, 200]),
                    'duration' => fake()->randomElement([30, 45, 60, 90]),
                    'buffer' => 15,
                    'is_active' => true,
                ]);
                $createdServices->push($service);
            }

            // Assign services each employee is able to perform (many-to-many relation)
            if ($createdServices->isNotEmpty()) {
                foreach ($employeeCompanyUsers as $companyUser) {
                    $servicesToAttach = $createdServices->random(rand(1, $createdServices->count()))->pluck('id');
                    $companyUser->services()->attach($servicesToAttach);
                }
            }

            // Create many reservations for the company (30-60 per company)
            $reservationCount = rand(30, 60);
            $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
            $companyHours = $company->getCompanyHours();

            for ($i = 0; $i < $reservationCount; $i++) {
                if ($createdServices->isEmpty()) {
                    continue;
                }

                $service = $createdServices->random();
                $qualifiedCompanyUsers = $employeeCompanyUsers->filter(
                    fn (CompanyUser $companyUser) => $companyUser->services()->whereKey($service->id)->exists()
                );

                // Pick a random day within the next 30 days that is open
                $dayOffset = fake()->numberBetween(0, 30);
                $startTime = now()->addDays($dayOffset)->startOfDay();

                // Find an open day
                $daysChecked = 0;
                while ($daysChecked < 30) {
                    $dayKey = strtolower($startTime->format('D'));
                    $dayHours = $companyHours[$dayKey] ?? ['closed' => true];
                    if (!($dayHours['closed'] ?? false)) {
                        break;
                    }
                    $startTime->addDay();
                    $daysChecked++;
                }

                // Random time within opening hours
                $dayKey = strtolower($startTime->format('D'));
                $dayHours = $companyHours[$dayKey] ?? ['open' => '09:00', 'close' => '18:00'];
                $openHour = (int) substr($dayHours['open'] ?? '09:00', 0, 2);
                $closeHour = (int) substr($dayHours['close'] ?? '18:00', 0, 2);
                $startHour = fake()->numberBetween($openHour, max($openHour, $closeHour - 1));
                $startTime->setHour($startHour)->setMinute(fake()->randomElement([0, 30]));

                // Ensure end time fits within closing hours
                $endTime = $startTime->copy()->addMinutes($service->duration + $service->buffer);
                $endHour = (int) $endTime->format('H');
                if ($endHour > $closeHour) {
                    $startTime->setHour($closeHour - 1)->setMinute(0);
                    $endTime = $startTime->copy()->addMinutes($service->duration + $service->buffer);
                }

                Reservation::create([
                    'company_id' => $company->id,
                    'service_id' => $service->id,
                    'company_user_id' => $qualifiedCompanyUsers->isNotEmpty() ? $qualifiedCompanyUsers->random()->id : null,
                    'user_id' => null, // set to null; can be replaced with the authenticated client ID
                    'client_name' => fake()->name(),
                    'client_email' => fake()->email(),
                    'client_phone' => fake()->phoneNumber(),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'notes' => fake()->randomElement([null, 'Special requests', 'First visit']),
                    'status' => fake()->randomElement($statuses),
                ]);
            }
        }
    }
}