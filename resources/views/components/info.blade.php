 <div class="row" x-data="{ expanded: localStorage.getItem('header_expanded') !== 'false' }" x-init="$watch('expanded', val => localStorage.setItem('header_expanded', val))">

        <div class="d-flex justify-content-between align-items-center">
            <h6 class="fw-bold">Boleslawiec - Create the city portal with us!</h6>
            <button @click="expanded = !expanded" class="btn btn-sm text-secondary border-0 p-0">
                <i class="bi" :class="expanded ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>
        <div x-show="expanded">
            <p class="col-md-12">
                Have interesting news, photos, or an opinion about events in Boleslawiec?
                Write an article and share it with the community.
                Win <b>100 PLN</b>
                Every month the best article author receives a cash prize.
            </p>
        </div>

        <div class="mt-0">
            @auth
            <a href="{{ route('article.create') }}">
                <i class="bi bi-pencil-square"></i> Add article
            </a>
            @else
            <a href="{{ route('login') }}">Log in to write</a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}">Register</a>
            @endif
            @endauth
        </div>
    </div>