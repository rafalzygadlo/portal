<div>
    @if($isOpen)
        <!-- Rzetelne tło modalu Bootstrapowe (backdrop) -->
        <div class="modal-backdrop fade show" style="z-index: 1050;"></div>
        
        <!-- Główne okno modalu ustawione na sztywno jako widoczne (d-block) -->
        <div class="modal d-block fade show" tabindex="-1" role="dialog" style="z-index: 1055; overflow-y: auto;">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content shadow-lg">
                    
                    <!-- Górna belka z krzyżykiem do zamykania -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $title ?? '' }}
                        </h5>
                        <button type="button" class="btn-close" aria-label="Close" wire:click="close"></button>
                    </div>

                    <!-- Główny kontener na Twój wyczyszczony formularz -->
                    <div class="modal-body">
                        @if($view)
                            @livewire($view, $params, key($view))
                        @endif
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>