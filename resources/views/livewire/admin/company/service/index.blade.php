
<div class="col">

    {{-- Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

        <div>
            <h1 class="h3 fw-semibold mb-1">Services</h1>
            <p class="text-muted mb-0">
                Manage the services offered by your company.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">

            {{-- Back --}}
            <a
                href="{{ route('admin.company.dashboard', ['company' => $company]) }}"
                class="btn btn-light border d-inline-flex align-items-center"
            >
                <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                Back
            </a>

            {{-- Active / Deleted --}}
            <div class="btn-group border rounded-3 overflow-hidden" role="group">

                <button
                    type="button"
                    class="btn  px-3 {{ !$showDeleted ? 'btn-primary' : 'btn-light' }}"
                    wire:click="$set('showDeleted', false)"
                >
                    <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                    Active
                </button>

                <button
                    type="button"
                    class="btn btn-sm px-3 {{ $showDeleted ? 'btn-primary' : 'btn-light' }}"
                    wire:click="$set('showDeleted', true)"
                >
                    <i class="bi bi-trash3 me-1" aria-hidden="true"></i>
                    Deleted
                </button>

            </div>

            {{-- Add --}}
            @unless ($showDeleted)
                <button
                    type="button"
                    wire:click="$dispatch('open', [])"
                    class="btn btn-primary d-inline-flex align-items-center"
                >
                    <i class="bi bi-plus-lg me-2" aria-hidden="true"></i>
                    Add service
                </button>
            @endunless

        </div>
    </div>


    {{-- Services --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        {{-- Card header --}}
        <div class="card-header bg-white border-bottom px-4 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h2 class="h6 fw-semibold mb-1">
                        {{ $showDeleted ? 'Deleted services' : 'Your services' }}
                    </h2>

                    <div class="small text-muted">
                        {{ $services->count() }}
                        {{ $services->count() === 1 ? 'service' : 'services' }}
                    </div>
                </div>

            </div>

        </div>


        @if ($services->isNotEmpty())

            {{-- Table --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-semibold">
                                Service
                            </th>

                            <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                                Details
                            </th>

                            <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                                Price
                            </th>

                            <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                                Team
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

                        @foreach ($services as $service)

                            <tr>

                                {{-- Service --}}
                                <td class="px-4 py-3">

                                    <div class="d-flex align-items-center gap-3">

                                        <div
                                            class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width: 42px; height: 42px;"
                                        >
                                            <i class="bi bi-briefcase" aria-hidden="true"></i>
                                        </div>

                                        <div>

                                            <div class="fw-semibold text-dark">
                                                {{ $service->name }}
                                            </div>

                                            @if ($service->description)
                                                <div class="small text-muted mt-1">
                                                    {{ \Illuminate\Support\Str::limit($service->description, 70) }}
                                                </div>
                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Details --}}
                                <td>

                                    <div class="d-flex flex-wrap gap-2">

                                        <span class="badge bg-light text-dark border fw-normal">
                                            <i class="bi bi-clock me-1 text-muted"></i>
                                            {{ $service->duration }} min
                                        </span>

                                        <span class="badge bg-light text-dark border fw-normal">
                                            <i class="bi bi-pause-circle me-1 text-muted"></i>
                                            {{ $service->buffer }} min break
                                        </span>

                                    </div>

                                </td>


                                {{-- Price --}}
                                <td>

                                    @if ($service->price)
                                        <div class="fw-semibold text-dark">
                                            {{ number_format($service->price, 2) }} PLN
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif

                                </td>


                                {{-- Assigned --}}
                                <td>

                                    @php
                                        $assignedPeople = $service->companyUsers()->with('user')->get();
                                    @endphp

                                    @if ($assignedPeople->isNotEmpty())

                                        <div class="d-flex flex-wrap gap-1">

                                            @foreach ($assignedPeople as $assignedPerson)

                                                <span
                                                    class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal"
                                                >
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $assignedPerson->display_name }}
                                                </span>

                                            @endforeach

                                        </div>

                                    @else

                                        <span class="small text-muted">
                                            <i class="bi bi-person-x me-1"></i>
                                            No one assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if ($service->trashed())

                                        <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                                            <i class="bi bi-trash3 me-1"></i>
                                            Deleted
                                        </span>

                                    @elseif ($service->is_active)

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

                                    @if ($service->trashed())

                                        <div class="d-inline-flex gap-2">

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-success"
                                                wire:click="restore({{ $service->id }})"
                                            >
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                Restore
                                            </button>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                wire:click="forceDelete({{ $service->id }})"
                                                wire:confirm="Permanently delete this service?"
                                            >
                                                <i class="bi bi-trash3 me-1"></i>
                                                Delete permanently
                                            </button>

                                        </div>

                                    @else

                                        <div class="dropdown">

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-light border"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                            >
                                                <i class="bi bi-three-dots"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                                                <li>
                                                    <button
                                                        type="button"
                                                        class="dropdown-item"
                                                        wire:click="$dispatch('open', [{{ $service->id }}])"
                                                    >
                                                        <i class="bi bi-pencil me-2 text-muted"></i>
                                                        Edit
                                                    </button>
                                                </li>

                                                <li>
                                                    <button
                                                        type="button"
                                                        class="dropdown-item"
                                                        wire:click="$dispatch('openAssign', [{{ $service->id }}])"
                                                    >
                                                        <i class="bi bi-people me-2 text-muted"></i>
                                                        Assign employees
                                                    </button>
                                                </li>

                                                <li>
                                                    <button
                                                        type="button"
                                                        class="dropdown-item"
                                                        wire:click="toggleActive({{ $service->id }})"
                                                    >
                                                        @if ($service->is_active)
                                                            <i class="bi bi-pause-circle me-2 text-muted"></i>
                                                            Deactivate
                                                        @else
                                                            <i class="bi bi-play-circle me-2 text-muted"></i>
                                                            Activate
                                                        @endif
                                                    </button>
                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>
                                                    <button
                                                        type="button"
                                                        class="dropdown-item text-danger"
                                                        wire:click="delete({{ $service->id }})"
                                                        wire:confirm="Are you sure you want to delete this service?"
                                                    >
                                                        <i class="bi bi-trash3 me-2"></i>
                                                        Delete
                                                    </button>
                                                </li>

                                            </ul>

                                        </div>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- Empty state --}}
            <div class="text-center py-5 px-4">

                <div class="mb-3">
                    <div
                        class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                        style="width: 72px; height: 72px;"
                    >
                        @if ($showDeleted)
                            <i class="bi bi-trash3 fs-3 text-muted"></i>
                        @else
                            <i class="bi bi-briefcase fs-3 text-muted"></i>
                        @endif
                    </div>
                </div>

                @if ($showDeleted)

                    <h3 class="h6 fw-semibold mb-1">
                        No deleted services
                    </h3>

                    <p class="text-muted small mb-0">
                        Deleted services will appear here.
                    </p>

                @else

                    <h3 class="h6 fw-semibold mb-1">
                        No services yet
                    </h3>

                    <p class="text-muted small mb-3">
                        Create your first service to start accepting bookings.
                    </p>

                    <button
                        type="button"
                        wire:click="$dispatch('open', [])"
                        class="btn btn-primary btn-sm"
                    >
                        <i class="bi bi-plus-lg me-1"></i>
                        Add service
                    </button>

                @endif

            </div>

        @endif

    </div>


    {{-- Modals / Livewire components --}}
    <livewire:admin.company.service.create :company="$company" />
    <livewire:admin.company.service.assign :company="$company" />

</div>
