<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-sm-12">
            <div class="card border-0">
                <div class="card-header bg-white text-center py-4">
                    <i class="bi bi-key-fill" style="font-size: 3rem;"></i>
                    <h3 class="mb-0 mt-2 fw-bold">Witaj z powrotem!</h3>
                </div>
                <div class="card-body p-5 text-center">
                    
                    @if ($linkSent)
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-envelope-check fs-1 d-block mb-2"></i>
                            <h5 class="alert-heading">Sprawdź skrzynkę!</h5>
                            <p class="mb-0">Wysłaliśmy link logowania na adres <strong>{{ $email }}</strong>.</p>
                        </div>
                    @else
                        <p class="text-muted mb-4">Podaj swój adres e-mail, aby otrzymać magiczny link do logowania. Jeśli nie masz konta, zostanie ono utworzone automatycznie.</p>

                        <form wire:submit.prevent="login">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" wire:model="email" placeholder="name@example.com" required>
                                <label for="email">Adres e-mail</label>
                                @error('email') <div class="invalid-feedback text-start">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-check mb-3 text-start">
                                <input class="form-check-input" type="checkbox" id="remember" wire:model="remember">
                                <label class="form-check-label" for="remember">
                                    Zapamiętaj mnie
                                </label>
                            </div>

                            <button class="w-100 btn btn-lg btn-dark" type="submit">
                                <span wire:loading.remove wire:target="login">
                                    <i class="bi bi-magic"></i> Wyślij magiczny link
                                </span>
                                <span wire:loading wire:target="login">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Wysyłanie...
                                </span>
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <small class="text-muted">Nie masz konta? Zostanie ono utworzone po podaniu adresu e-mail.</small>
                </div>
            </div>
        </div>
    </div>
</div>
