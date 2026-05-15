<div class="container-fluid py-5">
    <div class="row gy-4">
        <div class="col-12">
            Tytaj bede wszystkie nowosci i mozliwosc podbijania ogloszen, artykulow i innych rzeczy, ktore beda sie
            pojawiac na portalu. Bede tez informowal o nowych funkcjach i innych waznych rzeczach zwiazanych z portalem.
        </div>

        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Obraz</th>
                            <th scope="col">Tytuł</th>
                            <th scope="col">Opis</th>
                            <th scope="col">Kategorie</th>
                            <th scope="col">Dodane</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feed as $item)
                            <tr>
                                <td style="width:120px;">
                                    <a href="{{ route('offers.show', $item) }}" class="d-block">
                                        @if($item->images->isNotEmpty())
                                            <img loading="lazy" src="{{ asset('storage/' . $item->images->first()->path) }}" alt="{{ $item->title }}" class="img-fluid"
                                                style="height:60px; width:100px; object-fit:cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center border" style="height:60px; width:100px;">
                                                <i class="bi bi-image text-muted" style="font-size:1.5rem;"></i>
                                            </div>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('offers.show', $item) }}" class="text-decoration-none text-dark">{{ $item->title }}</a>
                                </td>
                                <td>{{ Str::limit($item->content, 100) }}</td>
                                <td>
                                    @foreach($item->categories as $category)
                                        <span class="badge bg-light text-dark border me-1">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-muted">{{ $item->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>