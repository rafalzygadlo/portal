<div class="col py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">Users</h1>
            <p class="text-muted mb-0">Users assigned to {{ $company->name }}.</p>
        </div>
        <a href="{{ route('admin.company.dashboard', ['company' => $company]) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Back
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="d-flex gap-2 mb-4">
        <button type="button" wire:click="openAttachModal" class="btn btn-primary">Attach existing user</button>
        <button type="button" wire:click="openCreateModal" class="btn btn-outline-primary">Create new user</button>
    </div>

    @if ($showAttachModal)
        <div class="modal-backdrop fade show"></div>
        <div class="modal d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.45);">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5">Attach existing user</h2>
                        <button type="button" class="btn-close" wire:click="$set('showAttachModal', false)" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="attach-email" class="form-label">User email</label>
                        <input id="attach-email" type="email" wire:model="attachEmail" class="form-control">
                        @error('attachEmail') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showAttachModal', false)">Cancel</button>
                        <button type="button" class="btn btn-primary" wire:click="attachUser">Attach user</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showCreateModal)
        <div class="modal-backdrop fade show"></div>
        <div class="modal d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.45);">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5">Create new user</h2>
                        <button type="button" class="btn-close" wire:click="$set('showCreateModal', false)" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="first-name" class="form-label">First name</label>
                                <input id="first-name" type="text" wire:model="firstName" class="form-control">
                                @error('firstName') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last-name" class="form-label">Last name</label>
                                <input id="last-name" type="text" wire:model="lastName" class="form-control">
                                @error('lastName') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="employee-email" class="form-label">Email</label>
                                <input id="employee-email" type="email" wire:model="email" class="form-control">
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="employee-password" class="form-label">Temporary password</label>
                                <input id="employee-password" type="password" wire:model="password" class="form-control">
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showCreateModal', false)">Cancel</button>
                        <button type="button" class="btn btn-primary" wire:click="createUser">Create and attach</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="table-responsive border shadow bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Added</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->pivot->owner ? 'bg-primary' : 'bg-secondary' }}">
                                {{ $user->pivot->owner ? 'Owner' : 'User' }}
                            </span>
                        </td>
                        <td>{{ $user->pivot->created_at?->format('d.m.Y H:i') ?? '-' }}</td>
                        <td class="text-end">
                            <button type="button" wire:click="openScheduleModal({{ $user->id }})" class="btn btn-sm btn-outline-secondary">Working hours</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No users assigned yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showScheduleModal)
        <div class="modal-backdrop fade show"></div>
        <div class="modal d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.45);">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title h5">Working hours &amp; days off</h2>
                        <button type="button" class="btn-close" wire:click="$set('showScheduleModal', false)" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Working hours</label>
                            @foreach ($scheduleWorkingHours as $day => $hours)
                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-3 text-capitalize small">{{ $day }}</div>
                                    <div class="col-4">
                                        <input type="time" wire:model="scheduleWorkingHours.{{ $day }}.open" class="form-control form-control-sm" @disabled($hours['closed'] ?? false)>
                                    </div>
                                    <div class="col-4">
                                        <input type="time" wire:model="scheduleWorkingHours.{{ $day }}.close" class="form-control form-control-sm" @disabled($hours['closed'] ?? false)>
                                    </div>
                                    <div class="col-1">
                                        <input type="checkbox" wire:model="scheduleWorkingHours.{{ $day }}.closed" class="form-check-input" title="Day off">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Time off</label>
                            @foreach ($scheduleUnavailablePeriods as $index => $period)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="small">{{ $period['start'] }} to {{ $period['end'] }}</span>
                                    <button type="button" wire:click="removeUnavailablePeriod({{ $index }})" class="btn btn-sm btn-outline-danger">Remove</button>
                                </div>
                            @endforeach
                            <div class="row g-2">
                                <div class="col-5"><input type="date" wire:model="scheduleTimeOffStart" class="form-control form-control-sm"></div>
                                <div class="col-5"><input type="date" wire:model="scheduleTimeOffEnd" class="form-control form-control-sm"></div>
                                <div class="col-2"><span class="form-text">Add on save</span></div>
                            </div>
                            @error('scheduleTimeOffEnd') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showScheduleModal', false)">Cancel</button>
                        <button type="button" class="btn btn-primary" wire:click="saveSchedule">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>