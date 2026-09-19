
<div class="col">

    {{-- Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

        <div>
            <h1 class="h3 fw-semibold mb-1">Resources</h1>
            <p class="text-muted mb-0">
                Manage people, facilities and equipment used for bookings.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">

            <a
                href="{{ route('admin.company.dashboard', ['company' => $company]) }}"
                class="btn btn-light border d-inline-flex align-items-center"
            >
                <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                Back
            </a>

            <button
                type="button"
                wire:click="$dispatch('open',[])"
                class="btn btn-primary d-inline-flex align-items-center"
            >
                <i class="bi bi-plus-lg me-2" aria-hidden="true"></i>
                Add resource
            </button>

        </div>
    </div>


    {{-- Resource groups --}}
    @forelse ($resourcesGrouped as $type => $resources)

        @php
            $typeLabel = match ($type) {
                'person' => 'People',
                'facility' => 'Facilities',
                'equipment' => 'Equipment',
                default => ucfirst($type),
            };

            $typeIcon = match ($type) {
                'person' => 'bi-people',
                'facility' => 'bi-building',
                'equipment' => 'bi-tools',
                default => 'bi-box',
            };

            $typeDescription = match ($type) {
                'person' => 'Employees or people available for bookings.',
                'facility' => 'Rooms, spaces and other facilities.',
                'equipment' => 'Equipment that can be booked or used.',
                default => 'Resources available for bookings.',
            };
        @endphp


        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

            {{-- Group header --}}
            <div class="card-header bg-white border-bottom px-4 py-3">

                <div class="d-flex justify-content-between align-items-center gap-3">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 44px; height: 44px;"
                        >
                            <i class="bi {{ $typeIcon }} fs-5" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h2 class="h6 fw-semibold mb-1">
                                {{ $typeLabel }}
                            </h2>

                            <p class="small text-muted mb-0">
                                {{ $typeDescription }}
                            </p>
                        </div>

                    </div>

                    <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                        {{ $resources->count() }}
                        {{ $resources->count() === 1 ? 'resource' : 'resources' }}
                    </span>

                </div>

            </div>


            {{-- Resources --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="bg-light">

                        <tr>

                            <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-semibold">
                                Resource
                            </th>

                            <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                                Details
                            </th>

                            <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                                Status
                            </th>

                            <th class="px-4 py-3 border-0 text-end small text-uppercase text-muted fw-semibold">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach ($resources as $resource)

                            <tr>

                                {{-- Resource --}}
                                <td class="px-4 py-3">

                                    <div class="d-flex align-items-center gap-3">

                                        <div
                                            class="rounded-3 bg-light text-secondary d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width: 40px; height: 40px;"
                                        >
                                            <i class="bi {{ $typeIcon }}" aria-hidden="true"></i>
                                        </div>

                                        <div>

                                            <div class="fw-semibold text-dark">
                                                {{ $resource->name }}
                                            </div>

                                            @if ($resource->type)
                                                <div class="small text-muted mt-1">
                                                    {{ $typeLabel }}
                                                </div>
                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Details --}}
                                <td>

                                    @if ($resource->type === 'person' && $resource->assignedUser)

                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal">
                                            <i class="bi bi-person-badge me-1"></i>
                                            {{ $resource->assignedUser->name }}
                                        </span>

                                    @elseif ($resource->type === 'equipment' && $resource->hourly_rate !== null)

                                        <span class="badge bg-light text-dark border fw-normal">
                                            <i class="bi bi-tag me-1 text-muted"></i>
                                            {{ number_format($resource->hourly_rate, 2) }} PLN/h
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if ($resource->is_active)

                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Active
                                        </span>

                                    @else

                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
                                            <i class="bi bi-pause-circle me-1"></i>
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-4 py-3 text-end">

                                    <button
                                        type="button"
                                        wire:click="$dispatch('open', [{{ $resource->id }}])"
                                        class="btn btn-sm btn-light border"
                                    >
                                        <i class="bi bi-pencil me-1"></i>
                                        Edit
                                    </button>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @empty

        {{-- Empty state --}}
        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body text-center py-5">

                <div
                    class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 72px; height: 72px;"
                >
                    <i class="bi bi-box-seam fs-2 text-muted"></i>
                </div>

                <h2 class="h6 fw-semibold mb-2">
                    No resources yet
                </h2>

                <p class="text-muted small mb-4">
                    Add people, facilities or equipment to start using them in bookings.
                </p>

                <button
                    type="button"
                    wire:click="$dispatch('open',[])"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Add resource
                </button>

            </div>

        </div>

    @endforelse


    {{-- Create / edit modal --}}
    <livewire:admin.company.resource.create :company="$company"/>

</div>

