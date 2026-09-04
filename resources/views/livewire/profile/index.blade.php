<div class="col">
    <div class="row">
        <!-- Profile Header & Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 text-center p-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-primary fw-bold"
                        style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ substr($user->first_name, 0, 1) }}
                    </div>
                </div>

                <h3 class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h3>
                <p class="text-muted">Joined: {{ $user->created_at->format('d.m.Y') }}</p>
                <p>{{ $user->email }}</p>

                <div class="card bg-primary-subtle border-0 mb-3">
                    <div class="card-body">
                        <div class="small text-uppercase text-muted">Saldo kredytów</div>
                        <div class="display-6 fw-bold text-primary">{{ $user->credits }} pkt</div>
                        <div class="small text-muted d-flex align-items-center justify-content-between gap-2 mt-2">
                            <span>Twój kod polecający: <strong>{{ $user->referral_code }}</strong></span>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="navigator.clipboard.writeText('{{ $user->referral_code }}').then(() => this.innerText = 'Skopiowano').catch(() => this.innerText = 'Błąd');">
                                Kopiuj
                            </button>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="applyReferralCode" class="mb-3">
                    <label for="referralCode" class="form-label small text-muted">Dodaj kod polecającego</label>
                    <div class="input-group">
                        <input id="referralCode" type="text" class="form-control" wire:model.defer="referralCode" placeholder="np. ABC123" maxlength="32">
                        <button class="btn btn-outline-primary" type="submit">Aktywuj</button>
                    </div>
                </form>

                <h5 class="card-title fw-bold mb-3">Quick actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('offer.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-briefcase me-2"></i> Create offer
                    </a>

                    <a href="{{ route('company.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-building me-2"></i> Create company
                    </a>
                    @if(config('modules.article'))
                    <a href="{{ route('article.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-newspaper me-2"></i> Write article
                    </a>
                    @endif
                    @if(config('modules.poll'))
                    <a href="{{ route('poll.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-bar-chart me-2"></i> Create poll
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Area with Tabs -->
        <div class="col-md-9">
            <!-- Tab Navigation -->
            <div class="mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($activeTab === 'overview') active @endif" 
                                id="overview-tab" 
                                wire:click="switchTab('overview')" 
                                type="button" 
                                role="tab" 
                                aria-controls="overview-panel" 
                                aria-selected="{{ $activeTab === 'overview' ? 'true' : 'false' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Overview
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($activeTab === 'offers') active @endif" 
                                id="offers-tab" 
                                wire:click="switchTab('offers')" 
                                type="button" 
                                role="tab" 
                                aria-controls="offers-panel" 
                                aria-selected="{{ $activeTab === 'offers' ? 'true' : 'false' }}">
                            <i class="bi bi-briefcase me-2"></i> Offers ({{ $user->offers->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($activeTab === 'companies') active @endif" 
                                id="companies-tab" 
                                wire:click="switchTab('companies')" 
                                type="button" 
                                role="tab" 
                                aria-controls="companies-panel" 
                                aria-selected="{{ $activeTab === 'companies' ? 'true' : 'false' }}">
                            <i class="bi bi-building me-2"></i> Companies ({{ $user->ownedCompanies->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($activeTab === 'articles') active @endif" 
                                id="articles-tab" 
                                wire:click="switchTab('articles')" 
                                type="button" 
                                role="tab" 
                                aria-controls="articles-panel" 
                                aria-selected="{{ $activeTab === 'articles' ? 'true' : 'false' }}">
                            <i class="bi bi-newspaper me-2"></i> Articles ({{ $user->articles->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($activeTab === 'polls') active @endif" 
                                id="polls-tab" 
                                wire:click="switchTab('polls')" 
                                type="button" 
                                role="tab" 
                                aria-controls="polls-panel" 
                                aria-selected="{{ $activeTab === 'polls' ? 'true' : 'false' }}">
                            <i class="bi bi-bar-chart me-2"></i> Polls ({{ $user->polls->count() ?? 0 }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($activeTab === 'comments') active @endif"
                                id="comments-tab"
                                wire:click="switchTab('comments')"
                                type="button"
                                role="tab"
                                aria-controls="comments-panel"
                                aria-selected="{{ $activeTab === 'comments' ? 'true' : 'false' }}">
                            <i class="bi bi-chat-left-text me-2"></i> Comments ({{ $user->comments->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($activeTab === 'favorites') active @endif"
                                id="favorites-tab"
                                wire:click="switchTab('favorites')"
                                type="button"
                                role="tab"
                                aria-controls="favorites-panel"
                                aria-selected="{{ $activeTab === 'favorites' ? 'true' : 'false' }}">
                            <i class="bi bi-heart me-2"></i> Favorites ({{ $user->favorites->count() }})
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Overview Tab -->
                <div class="tab-pane fade @if($activeTab === 'overview') show active @endif" 
                     id="overview-panel" 
                     role="tabpanel" 
                     aria-labelledby="overview-tab">
                    <div class="card border-0 p-4">
                        <h5 class="card-title fw-bold mb-3">Welcome to your dashboard!</h5>
                        <p class="text-muted">Manage your content from here. Use the tabs above to view and edit your:</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Offers
                                <span class="badge bg-primary rounded-pill">{{ $user->offers->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Companies
                                <span class="badge bg-success rounded-pill">{{ $user->ownedCompanies->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Articles
                                <span class="badge bg-info rounded-pill">{{ $user->articles->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Polls
                                <span class="badge bg-warning rounded-pill">{{ $user->polls->count() ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Comments
                                <span class="badge bg-secondary rounded-pill">{{ $user->comments->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Favorites
                                <span class="badge bg-danger rounded-pill">{{ $user->favorites->count() }}</span>
                            </li>
                        </ul>

                        <div class="mt-4">
                            <h6 class="fw-bold mb-3">Historia kredytów</h6>
                            @if($user->creditTransactions->isEmpty())
                                <div class="alert alert-light border mb-0">Brak transakcji kredytowych.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Typ</th>
                                                <th>Opis</th>
                                                <th class="text-end">Kwota</th>
                                                <th class="text-end">Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->creditTransactions()->latest()->take(10)->get() as $transaction)
                                                <tr>
                                                    <td>
                                                        <span class="badge {{ $transaction->amount > 0 ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                                            {{ $transaction->type }}
                                                        </span>
                                                    </td>
                                                    <td class="text-muted small">{{ $transaction->description ?? 'Transakcja' }}</td>
                                                    <td class="text-end fw-bold {{ $transaction->amount > 0 ? 'text-success' : 'text-secondary' }}">
                                                        {{ $transaction->amount > 0 ? '+' : '' }}{{ $transaction->amount }} pkt
                                                    </td>
                                                    <td class="text-end text-muted small">{{ $transaction->created_at->format('d.m.Y H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Offers Tab -->
                <div class="tab-pane fade @if($activeTab === 'offers') show active @endif" 
                     id="offers-panel" 
                     role="tabpanel" 
                     aria-labelledby="offers-tab">
                    <livewire:profile.offer.index />
                </div>

                <!-- Companies Tab -->
                <div class="tab-pane fade @if($activeTab === 'companies') show active @endif" 
                     id="companies-panel" 
                     role="tabpanel" 
                     aria-labelledby="companies-tab">
                    <livewire:profile.company.index />
                </div>

                <!-- Articles Tab -->
                <div class="tab-pane fade @if($activeTab === 'articles') show active @endif" 
                     id="articles-panel" 
                     role="tabpanel" 
                     aria-labelledby="articles-tab">
                    <livewire:profile.article.index />
                </div>

                <!-- Polls Tab -->
                 @if(config('modules.poll'))
                <div class="tab-pane fade @if($activeTab === 'polls') show active @endif" 
                     id="polls-panel" 
                     role="tabpanel" 
                     aria-labelledby="polls-tab">
                    <livewire:profile.poll.index />
                </div>
                @endif
                <!-- Comments Tab -->
                <div class="tab-pane fade @if($activeTab === 'comments') show active @endif"
                     id="comments-panel"
                     role="tabpanel"
                     aria-labelledby="comments-tab">
                    <livewire:profile.comment.index />
                </div>

                <!-- Favorites Tab -->
                <div class="tab-pane fade @if($activeTab === 'favorites') show active @endif"
                     id="favorites-panel"
                     role="tabpanel"
                     aria-labelledby="favorites-tab">
                    <livewire:profile.favorite.index />
                </div>
            </div>
        </div>
    </div>
</div>
