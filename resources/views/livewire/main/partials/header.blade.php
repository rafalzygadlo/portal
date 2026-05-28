<div class="row justify-content-center mb-4">
        <div class="col-12 col-xl-8">
            <div class="card border-0 bg-light rounded-4 p-4 text-center shadow-sm position-relative">
            
                <h4 class="fw-black text-dark mb-2">Strumień Aktywności</h4>
                <p class="text-secondary small mx-auto mb-4" style="max-width: 600px;">
                    Tutaj znajdziesz wszystkie nowości, ogłoszenia i artykuły pojawiające się na portalu. 
                    Będziemy Cię tu również na bieżąco informować o nowych funkcjach i ważnych wydarzeniach!
                </p>

                <div class="dropdown">
                    <button class="btn btn-primary btn-lg 1rounded-pill px-4 py-2.5 shadow-sm dropdown-toggle fw-bold" 
                            type="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false" 
                            style="font-size: 0.95rem;">
                        <i class="bi bi-plus-circle-fill me-2"></i> Dodaj nową treść
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2 p-2">
                        <li>
                            <a class="dropdown-item py-2.5 rounded-2 d-flex align-items-center gap-2" 
                               wire:click="$dispatch('openModal', ['offer.create','Dodaj ogłoszenie'])" 
                               href="#">
                                <i class="bi bi-megaphone-fill text-primary fs-5"></i> 
                                <span class="fw-medium">Dodaj ogłoszenie</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2.5 rounded-2 d-flex align-items-center gap-2" 
                               wire:click="$dispatch('openModal', ['business.create','Dodaj firmę'])" 
                               href="#">
                                <i class="bi bi-buildings-fill text-success fs-5"></i> 
                                <span class="fw-medium">Dodaj firmę</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2.5 rounded-2 d-flex align-items-center gap-2" 
                               wire:click="$dispatch('openModal', ['todo.create','Dodaj zadanie'])" 
                               href="#">
                                <i class="bi bi-check-circle-fill text-info fs-5"></i> 
                                <span class="fw-medium">Dodaj zadanie</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>