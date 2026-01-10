<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0">
                <div class="card-body p-5 text-center">
                    
                    <h3 class="mb-4 fw-bold">Logowanie / Rejestracja</h3>

                    @if ($linkSent)
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-envelope-check fs-1 d-block mb-2"></i>
                            <h5 class="alert-heading">Sprawdź skrzynkę!</h5>
                            <p class="mb-0">Wysłaliśmy link logowania na adres <strong>{{ $email }}</strong>.</p>
                        </div>
                    @else
                        <p class="text-muted mb-4">Podaj swój adres e-mail. Jeśli masz konto, zalogujemy Cię. Jeśli nie - utworzymy je automatycznie.</p>

                        <form wire:submit.prevent="login">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" wire:model="email" placeholder="name@example.com">
                                <label for="email">Adres e-mail</label>
                                @error('email') <div class="invalid-feedback text-start">{{ $message }}</div> @enderror
                            </div>

                            <button class="w-100 btn btn-lg btn-primary" type="submit">
                                <i class="bi bi-magic"></i> Wyślij magiczny link
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
