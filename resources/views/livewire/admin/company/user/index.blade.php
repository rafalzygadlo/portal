
<div class="col">

    {{-- Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

        <div>
            <h1 class="h3 fw-semibold mb-1">Users</h1>

            <p class="text-muted mb-0">
                Manage users assigned to {{ $company->name }}.
            </p>
        </div>

        <a
            href="{{ route('admin.company.dashboard', ['company' => $company]) }}"
            class="btn btn-light border d-inline-flex align-items-center"
        >
            <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
            Back
        </a>

    </div>

    {{-- Success message --}}
    @if (session('success'))

        <div
            class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4"
            role="alert"
        >
            <i class="bi bi-check-circle me-2"></i>
            <span>{{ session('success') }}</span>
        </div>

    @endif

    {{-- Actions --}}
    <div class="d-flex flex-wrap gap-2 mb-4">

        <button
            type="button"
            wire:click="openAttachModal"
            class="btn btn-primary d-inline-flex align-items-center"
        >
            <i class="bi bi-person-plus me-2"></i>
            Attach existing user
        </button>

        <button
            type="button"
            wire:click="openCreateModal"
            class="btn btn-light border d-inline-flex align-items-center"
        >
            <i class="bi bi-person-add me-2"></i>
            Create new user
        </button>

    </div>

    {{-- Users --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-bottom px-4 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h2 class="h6 fw-semibold mb-1">
                        Company users
                    </h2>

                    <p class="small text-muted mb-0">
                        Users who have access to this company.
                    </p>
                </div>

                <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                    {{ $users->count() }}
                    {{ $users->count() === 1 ? 'user' : 'users' }}
                </span>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="bg-light">

                    <tr>
                        <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-semibold">
                            User
                        </th>

                        <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                            Display name
                        </th>

                        <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                            Email
                        </th>

                        <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                            Role
                        </th>

                        <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                            Added
                        </th>

                        <th class="px-4 py-3 border-0 text-end small text-uppercase text-muted fw-semibold">
                            Actions
                        </th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($users as $user)

                        @php
                            $initials = collect(explode(' ', trim($user->name)))
                                ->filter()
                                ->map(fn ($part) => mb_substr($part, 0, 1))
                                ->take(2)
                                ->implode('');
                        @endphp

                        <tr>

                            {{-- User --}}
                            <td class="px-4 py-3">

                                <div class="d-flex align-items-center gap-3">

                                    <div
                                        class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0 fw-semibold"
                                        style="width: 42px; height: 42px;"
                                    >
                                        {{ strtoupper($initials) }}
                                    </div>

                                    <div>

                                        <div class="fw-semibold text-dark">
                                            {{ $user->name }}
                                        </div>

                                        @if ($user->pivot->owner)
                                            <div class="small text-muted mt-1">
                                                Company owner
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </td>

                            {{-- Display name --}}
                            <td>

                                @if ($user->pivot->display_name)

                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-normal px-3 py-2">
                                        <i class="bi bi-person-badge me-1"></i>
                                        {{ $user->pivot->display_name }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>

                            {{-- Email --}}
                            <td>

                                <span class="text-dark">
                                    {{ $user->email }}
                                </span>

                            </td>

                            {{-- Role --}}
                            <td>

                                @if ($user->pivot->owner)

                                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                                        <i class="bi bi-shield-check me-1"></i>
                                        Owner
                                    </span>

                                @else

                                    <span class="badge rounded-pill bg-light text-secondary border px-3 py-2">
                                        <i class="bi bi-person me-1"></i>
                                        User
                                    </span>

                                @endif

                            </td>

                            {{-- Added --}}
                            <td>

                                <span class="small text-muted">
                                    {{ $user->pivot->created_at?->format('d.m.Y H:i') ?? '-' }}
                                </span>

                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-3 text-end">

                                <div class="dropdown">

                                    <button
                                        class="btn btn-sm btn-light border dropdown-toggle"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        Actions
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                                        <li>
                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                wire:click="openEditModal({{ $user->id }})"
                                            >
                                                <i class="bi bi-pencil me-2 text-muted"></i>
                                                Edit name
                                            </button>
                                        </li>

                                        <li>
                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                wire:click="$dispatch('openFreeDays', [{{ $user->id }}])"
                                            >
                                                <i class="bi bi-calendar2-x me-2 text-muted"></i>
                                                Free days
                                            </button>
                                        </li>

                                        <li>
                                            <button
                                                type="button"
                                                class="dropdown-item"
                                                wire:click="$dispatch('openWorkingHours', [{{ $user->id }}])"
                                            >
                                                <i class="bi bi-clock me-2 text-muted"></i>
                                                Working hours
                                            </button>
                                        </li>

                                    </ul>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-4">

                                <div class="text-center py-5">

                                    <div
                                        class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                                        style="width: 72px; height: 72px;"
                                    >
                                        <i class="bi bi-people fs-2 text-muted"></i>
                                    </div>

                                    <h3 class="h6 fw-semibold mb-2">
                                        No users assigned yet
                                    </h3>

                                    <p class="text-muted small mb-4">
                                        Attach an existing user or create a new user for this company.
                                    </p>

                                    <div class="d-flex justify-content-center flex-wrap gap-2">

                                        <button
                                            type="button"
                                            wire:click="openAttachModal"
                                            class="btn btn-primary"
                                        >
                                            <i class="bi bi-person-plus me-1"></i>
                                            Attach existing user
                                        </button>

                                        <button
                                            type="button"
                                            wire:click="openCreateModal"
                                            class="btn btn-light border"
                                        >
                                            <i class="bi bi-person-add me-1"></i>
                                            Create new user
                                        </button>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Attach existing user modal --}}
    @if ($showAttachModal)

        <div class="modal-backdrop fade show"></div>

        <div
            class="modal d-block"
            tabindex="-1"
            role="dialog"
            style="background: rgba(0, 0, 0, 0.45);"
        >

            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content border-0 shadow rounded-4">

                    <div class="modal-header px-4 py-3">

                        <div>
                            <h2 class="modal-title h5 fw-semibold mb-1">
                                Attach existing user
                            </h2>

                            <p class="small text-muted mb-0">
                                Give an existing account access to this company.
                            </p>
                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            wire:click="$set('showAttachModal', false)"
                            aria-label="Close"
                        ></button>

                    </div>

                    <div class="modal-body px-4 py-4">

                        <label for="attach-email" class="form-label fw-semibold">
                            User email
                        </label>

                        <input
                            id="attach-email"
                            type="email"
                            wire:model="attachEmail"
                            class="form-control"
                            placeholder="user@example.com"
                        >

                        @error('attachEmail')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="modal-footer px-4 py-3">

                        <button
                            type="button"
                            class="btn btn-light border"
                            wire:click="$set('showAttachModal', false)"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            class="btn btn-primary"
                            wire:click="attachUser"
                        >
                            <i class="bi bi-person-plus me-1"></i>
                            Attach user
                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Create user modal --}}
    @if ($showCreateModal)

        <div class="modal-backdrop fade show"></div>

        <div
            class="modal d-block"
            tabindex="-1"
            role="dialog"
            style="background: rgba(0, 0, 0, 0.45);"
        >

            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

                <div class="modal-content border-0 shadow rounded-4">

                    <div class="modal-header px-4 py-3">

                        <div>
                            <h2 class="modal-title h5 fw-semibold mb-1">
                                Create new user
                            </h2>

                            <p class="small text-muted mb-0">
                                Create an account and attach it to this company.
                            </p>
                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            wire:click="$set('showCreateModal', false)"
                            aria-label="Close"
                        ></button>

                    </div>

                    <div class="modal-body px-4 py-4">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label for="first-name" class="form-label fw-semibold">
                                    First name
                                </label>

                                <input
                                    id="first-name"
                                    type="text"
                                    wire:model="firstName"
                                    class="form-control"
                                >

                                @error('firstName')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label for="last-name" class="form-label fw-semibold">
                                    Last name
                                </label>

                                <input
                                    id="last-name"
                                    type="text"
                                    wire:model="lastName"
                                    class="form-control"
                                >

                                @error('lastName')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label for="employee-email" class="form-label fw-semibold">
                                    Email
                                </label>

                                <input
                                    id="employee-email"
                                    type="email"
                                    wire:model="email"
                                    class="form-control"
                                >

                                @error('email')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label for="employee-password" class="form-label fw-semibold">
                                    Temporary password
                                </label>

                                <input
                                    id="employee-password"
                                    type="password"
                                    wire:model="password"
                                    class="form-control"
                                >

                                @error('password')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer px-4 py-3">

                        <button
                            type="button"
                            class="btn btn-light border"
                            wire:click="$set('showCreateModal', false)"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            class="btn btn-primary"
                            wire:click="createUser"
                        >
                            <i class="bi bi-person-plus me-1"></i>
                            Create and attach
                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Edit display name modal --}}
    @if ($showEditModal)

        <div class="modal-backdrop fade show"></div>

        <div
            class="modal d-block"
            tabindex="-1"
            role="dialog"
            style="background: rgba(0, 0, 0, 0.45);"
        >

            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content border-0 shadow rounded-4">

                    <div class="modal-header px-4 py-3">

                        <div>
                            <h2 class="modal-title h5 fw-semibold mb-1">
                                Edit display name
                            </h2>

                            <p class="small text-muted mb-0">
                                Choose how this user should appear in the company.
                            </p>
                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            wire:click="$set('showEditModal', false)"
                            aria-label="Close"
                        ></button>

                    </div>

                    <div class="modal-body px-4 py-4">

                        <label for="edit-display-name" class="form-label fw-semibold">
                            Display name
                        </label>

                        <input
                            id="edit-display-name"
                            type="text"
                            wire:model="editDisplayName"
                            class="form-control"
                            placeholder="e.g. John the Barber"
                        >

                        <div class="form-text mt-2">
                            Leave empty to use the user's real name.
                        </div>

                        @error('editDisplayName')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="modal-footer px-4 py-3">

                        <button
                            type="button"
                            class="btn btn-light border"
                            wire:click="$set('showEditModal', false)"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            class="btn btn-primary"
                            wire:click="saveDisplayName"
                        >
                            <i class="bi bi-check-lg me-1"></i>
                            Save
                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Existing Livewire components --}}
    <livewire:admin.company.user.free-days :company="$company" />
    <livewire:admin.company.user.working-hours :company="$company" />

</div>
