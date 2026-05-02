<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Globalne błędy --}}
            @if ($errors->any())
                <div class="text-danger mb-3">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            {{-- Status sukcesu (np. po wysłaniu linku) --}}
            @if ($status)
                <div class="alert alert-success mb-3" role="alert">
                    {{ $status }}
                </div>
            @endif

            <div wire:loading>{{ __('login.loading') }}</div>

            <div class="box">
                <div class="card-body">
                    <form wire:submit.prevent="sendResetLink">
                        @csrf
                        <div class="row">
                            <!-- Lewa kolumna: Tytuł i opis -->
                            <div class="col-md-6 d-flex flex-column justify-content-center p-4">
                                <h1 class="box-header mb-3">{{ __('passwords.reset_title') }}</h1>
                                <p class="mb-4 text-muted">
                                    {{ __('passwords.description') }}
                                </p>
                            </div>

                            <!-- Prawa kolumna: Formularz email -->
                            <div class="col-md-6 p-4">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="email" class="col-form-label">{{ __('login.email') }}</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="email" 
                                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                               wire:model.defer="email" 
                                               id="email" 
                                               autocomplete="email" 
                                               autofocus>
                                        
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            {{ __('passwords.send_link') }}
                                        </button>

                                        <a class="btn btn-link d-block text-center mt-2" href="{{ route('login') }}">
                                            {{ __('login.back_to_login') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <p class="small text-muted mt-3">
                {{ __('global.privacy_notice') }}
            </p>
        </div>
    </div>
</div>