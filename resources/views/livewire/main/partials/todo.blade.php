
<div  class="card shadow-sm border-1 rounded-4 overflow-hidden flex-row flex-lg-column transition-all hover-shadow">

    <div class="card-body p-3 p-lg-4 d-flex flex-column col-8 col-lg-12">
        <!-- Tytuł -->
        <h6 class="card-title fw-bold mb-1 mb-lg-2">
            <a href="" class="text-decoration-none text-dark hover-primary line-clamp-2">
                {{ $item->title }}
            </a>
        </h6>

        <!-- Krótki opis (Ukryty na bardzo małych ekranach dla oszczędności miejsca) -->
        <p class="card-text text-secondary small flex-grow-1 mb-2 d-none d-sm-block line-clamp-2"
            style="font-size: 0.825rem;">
            {{ Str::limit($item->content ?? $item->description, 75) }}
        </p>

        <!-- data dodania -->
        <div
            class="mt-auto d-flex flex-wrap align-items-center justify-content-between gap-1 pt-1 border-top border-light">
            <small class="text-muted fw-medium" style="font-size: 0.72rem;">
                {{ $item->created_at->diffForHumans(null, true) }} {{-- Krótka forma czasu np. "2 dni temu" --}}
            </small>
        </div>
    </div>

</div>