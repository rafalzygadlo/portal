<div class="container min-vh-100 d-flex flex-column justify-content-center ">

    <div class="row justify-content-center">
        <div class="col">
            @if ($errors->any())
                <div class="text-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif
            <div wire:loading>{{ __('login.loading') }}</div>
            <div class="box">
                <div class="card-body">
                    <form wire:submit.prevent="login" novalidate>
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-0 text-muted lead">{{ __('login.welcome_message') }}</p>
                            </div>
                            <div class="col-md-12">
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="email" class="col-form-label">{{ __('login.email') }}</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" wire:model.defer="email" autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <label for="password" class="col-form-label">{{ __('login.password') }}</label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" wire:model.defer="password" name="password" autocomplete="current-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model.defer="remember" id="remember">
                                            <label class="form-check-label" for="remember">
                                                {{ __('login.remember_me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary w-100">
                                            {{ __('login.submit') }}
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link d-block text-center mt-2" href="{{ route('password.request') }}">
                                                {{ __('login.forgot_password') }}
                                            </a>
                                        @endif

                                        <div class="text-center mt-3 small text-muted">
                                            {{ __('login.no_account') }} <a href="{{ route('register') }}">{{ __('login.register_here') }}</a>.
                                        </div>
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
