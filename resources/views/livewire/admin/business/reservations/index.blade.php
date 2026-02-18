        <div>
            <h2 class="h4 fw-bold mb-4">Rezerwacje</h2>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Klient</th>
                            <th>Usługa</th>
                            <th>Data i czas</th>
                            <th>Status</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $reservation)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $reservation->client_name }}</div>
                                    <div class="small text-muted">{{ $reservation->client_email }}</div>
                                </td>
                                <td>{{ $reservation->service->name }}</td>
                                <td>
                                    {{ $reservation->start_time->format('d.m.Y H:i') }}<br>
                                    <span class="small text-muted">{{ $reservation->service->duration_minutes }} min</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ 
                                        $reservation->status === 'confirmed' ? 'bg-success' :
                                        ($reservation->status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark')
                                    }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if ($reservation->status === 'pending')
                                            <button 
                                                wire:click="confirmReservation({{ $reservation->id }})"
                                                class="btn btn-sm btn-link text-success text-decoration-none p-0"
                                            >
                                                Potwierdź
                                            </button>
                                        @endif
                                        @if ($reservation->status !== 'cancelled')
                                            <button 
                                                wire:click="cancelReservation({{ $reservation->id }})"
                                                class="btn btn-sm btn-link text-danger text-decoration-none p-0"
                                            >
                                                Anuluj
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Brak rezerwacji
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $reservations->links() }}
            </div>
        </div>
    @endif

