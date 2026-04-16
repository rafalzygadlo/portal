<div>
    @if($reported)
        <div class="alert alert-success d-inline-block">
            <i class="bi bi-check-circle-fill me-2"></i> Thank you for the report. The administrator will review this article.
        </div>
    @else
        @if(!$showForm)
            <button wire:click="toggleForm" class="btn btn-link text-danger text-decoration-none p-0">
                <i class="bi bi-flag"></i> Report violation
            </button>
        @else
            <div class="card mt-2 border-danger" style="max-width: 500px;">
                <div class="card-body bg-light">
                    <h6 class="card-title text-danger fw-bold mb-3">Report this article</h6>
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <textarea wire:model="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" placeholder="Briefly describe why you are reporting this article..."></textarea>
                            @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" wire:click="toggleForm" class="btn btn-sm btn-outline-secondary">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-danger">Submit report</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>