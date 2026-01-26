<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\ReservationService;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Pobierz dostępne sloty dla usługi i daty
     */
    public function getAvailableSlots(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'service_id' => 'required|exists:reservation_services,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $business = Business::findOrFail($validated['business_id']);
        $service = ReservationService::findOrFail($validated['service_id']);
        $date = Carbon::parse($validated['date']);

        // Sprawdź godziny pracy
        $dayKey = strtolower($date->format('D'));
        $dayMap = ['mon' => 'mon', 'tue' => 'tue', 'wed' => 'wed', 'thu' => 'thu', 'fri' => 'fri', 'sat' => 'sat', 'sun' => 'sun'];
        $dayKey = $dayMap[$dayKey] ?? null;

        $businessHours = $business->getBusinessHours();
        
        if (!$dayKey || ($businessHours[$dayKey]['closed'] ?? false)) {
            return response()->json(['slots' => []]);
        }

        // Generuj sloty
        $openTime = Carbon::parse($businessHours[$dayKey]['open']);
        $closeTime = Carbon::parse($businessHours[$dayKey]['close']);
        $slotDuration = $business->booking_slot_duration;
        $serviceDuration = $service->duration_minutes;
        $buffer = $service->buffer_minutes;

        $slots = [];
        $current = $date->copy()->setHour($openTime->hour)->setMinute($openTime->minute)->second(0);
        $dayClose = $date->copy()->setHour($closeTime->hour)->setMinute($closeTime->minute)->second(0);

        while ($current->copy()->addMinutes($serviceDuration) <= $dayClose) {
            if (Reservation::isTimeSlotAvailable(
                $business->id,
                $service->id,
                $current->format('Y-m-d H:i:s'),
                $current->copy()->addMinutes($serviceDuration)->format('Y-m-d H:i:s')
            )) {
                $slots[] = $current->format('H:i');
            }

            $current->addMinutes($slotDuration);
        }

        return response()->json(['slots' => $slots]);
    }

    /**
     * Zapisz rezerwację
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'service_id' => 'required|exists:reservation_services,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'client_phone' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $business = Business::findOrFail($validated['business_id']);
        $service = ReservationService::findOrFail($validated['service_id']);
        
        $startTime = Carbon::parse($validated['date'] . ' ' . $validated['time']);
        
        // Sprawdzenie dostępności
        if (!Reservation::isTimeSlotAvailable(
            $business->id,
            $service->id,
            $startTime->format('Y-m-d H:i:s'),
            $startTime->copy()->addMinutes($service->duration_minutes)->format('Y-m-d H:i:s')
        )) {
            return response()->json(['error' => 'Wybrany termin jest niedostępny'], 422);
        }

        $reservation = Reservation::create([
            'business_id' => $business->id,
            'reservation_service_id' => $service->id,
            'user_id' => auth()->id(),
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'],
            'start_time' => $startTime,
            'end_time' => $startTime->copy()->addMinutes($service->duration_minutes),
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        // TODO: Wyślij email potwierdzenia

        return response()->json([
            'message' => 'Rezerwacja została złożona',
            'reservation_id' => $reservation->id,
        ], 201);
    }
}
