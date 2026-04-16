<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-sm-12">
            <div class="card border-1">
                <div class="card-header  text-center">
                    <i class="bi bi-key-fill" style="font-size: 3rem;"></i>
                </div>
                <div class="card-body p-5 text-center">
                    
                    @if ($linkSent)
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-envelope-check fs-1 d-block mb-2"></i>
                            <h5 class="alert-heading">Check your inbox!</h5>
                            <p class="mb-0">We sent the login link to <strong>{{ $email }}</strong>.</p>
                        </div>
                    @else
                        <p class="text-muted mb-4">Enter your email address to receive a magic login link. If you don't have an account, one will be created automatically.</p>

                        <form wire:submit.prevent="login">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" wire:model="email" placeholder="name@example.com" required>
                                <label for="email">Email address</label>
                                @error('email') <div class="invalid-feedback text-start">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-check mb-3 text-start">
                                <input class="form-check-input" type="checkbox" id="remember" wire:model="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            <button class="w-100 btn btn-lg btn-dark" type="submit">
                                <span wire:loading.remove wire:target="login">
                                    <i class="bi bi-magic"></i> Send magic link
                                </span>
                                <span wire:loading wire:target="login">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Sending...
                                </span>
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <small class="text-muted">Don't have an account? One will be created when you enter your email.</small>
                </div>
            </div>
        </div>
    </div>
</div>
