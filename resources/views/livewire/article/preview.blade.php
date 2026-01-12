<div class="article-preview">
    <h1 class="fw-bold mb-3">{{ $title ?: 'Brak tytułu' }}</h1>

    @if ($photo)
        <img src="{{ $photo->temporaryUrl() }}" class="img-fluid mb-4 rounded" style="max-height: 400px; width: 100%; object-fit: cover;" alt="Podgląd zdjęcia">
    @endif

    <div class="article-content fs-5 lh-lg">
        {!! nl2br(e($content)) !!}
    </div>

    <div class="mt-5 pt-3 border-top text-center">
        <button type="button" wire:click="edit" class="btn btn-outline-secondary me-2">Wróć do edycji</button>
        <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Opublikuj artykuł</button>
    </div>
</div>