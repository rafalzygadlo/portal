<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="box">
                <div class="card-body">
                    <form wire:submit.prevent="register" novalidate>

                        <div class="row">
                            <!-- Left Column: Title, Description, Checkboxes -->
                            <div class="col-md-6 d-flex flex-column justify-content-center p-4">
                                <h1 class="box-header mb-3">{{ __('register.title') }}</h1>
                                <p class="mb-4 text-muted">
                                    {{ __('register.description') }}
                                </p>


                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" wire:model.defer="terms" id="terms" required>
                                            <label class="form-check-label" for="terms">
                                                {{ __('register.terms') }}
                                            </label>
                                            @error('terms')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model.defer="marketing" id="marketing">
                                            <label class="form-check-label" for="marketing">
                                                {{ __('register.marketing') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Input Fields -->
                            <div class="col-md-6 p-4">
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="email" class="col-form-label">{{ __('register.email') }}</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" wire:model.defer="email" required autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="password" class="col-form-label">{{ __('register.password') }}</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" wire:model.defer="password" required autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <label for="password-confirm" class="col-form-label">{{ __('register.confirm_password') }}</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input id="password-confirm" type="password" class="form-control form-control-lg" wire:model.defer="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            {{ __('register.submit') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                     <div wire:loading>{{ __('register.loading') }}</div>
                </div>
            </div>
              <p class="small text-muted mt-3">
                                 {{ __('global.privacy_notice') }}
                                </p>
        </div>
    </div>
</div>
