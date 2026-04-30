<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @if ($errors->any())
                <div class="text-danger mb-3">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <div wire:loading>{{ __('login.loading') }}</div>

            <div class="box">
                <div class="card-body">
                    <form wire:submit.prevent="resetPassword">
                        @csrf
                        <div class="row">
                            <!-- Lewa kolumna: Tytuł i opis -->
                            <div class="col-md-6 d-flex flex-column justify-content-center p-4">
                                <h1 class="box-header mb-3">{{ __('passwords.new_password_title') }}</h1>
                                <p class="mb-4 text-muted">
                                    {{ __('passwords.new_password_instruction') }}
                                </p>
                            </div>

                            <!-- Prawa kolumna: Pola formularza -->
                            <div class="col-md-6 p-4">
                                <!-- Email (zazwyczaj tylko do odczytu lub ukryty, ale zachowuję wg Twojego kodu) -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="email" class="col-form-label">{{ __('login.email') }}</label>
                                        <input id="email" type="email" 
                                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                               wire:model.defer="email" autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nowe Hasło -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="password" class="col-form-label">{{ __('login.password') }}</label>
                                        <input id="password" type="password" 
                                               class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                               wire:model.defer="password" autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Potwierdzenie Hasła -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label for="password-confirm" class="col-form-label">{{ __('login.confirm_password') }}</label>
                                        <input id="password-confirm" type="password" 
                                               class="form-control form-control-lg" 
                                               wire:model.defer="password_confirmation" autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            {{ __('passwords.reset_button') }}
                                        </button>
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