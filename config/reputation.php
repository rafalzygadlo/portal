<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Punkty Reputacji (Karmy)
    |--------------------------------------------------------------------------
    |
    | Tutaj definiujemy, ile punktów użytkownik zyskuje lub traci za
    | poszczególne akcje na platformie. Używanie kluczy zamiast "magicznych"
    | liczb w kodzie sprawia, że system jest czytelny i łatwy w zarządzaniu.
    |
    */
    'points' => [
        'report_created'   => 1,    // Nowe zgłoszenie (może być 0, punkty przyznawane po akceptacji)
        'report_approved'  => 10,   // Zgłoszenie zaakceptowane przez moderatora
        'report_rejected'  => -5,   // Zgłoszenie odrzucone przez moderatora

        'initiative_created' => 2,  // Nowa inicjatywa
        'comment_created'    => 1,  // Nowy komentarz

        'vote_up_received'   => 2,  // Otrzymany głos na plus na swoim wpisie
        'vote_down_received' => -2, // Otrzymany głos na minus na swoim wpisie
        'vote_down_given'    => -1, // Koszt oddania głosu na minus na cudzym wpisie
    ],

    /*
    |--------------------------------------------------------------------------
    | Progi Reputacji
    |--------------------------------------------------------------------------
    |
    | Definiujemy progi reputacji, które mogą wpływać na uprawnienia
    | użytkownika, np. blokada dodawania treści.
    |
    */
    'thresholds' => [
        'can_create_content' => 0, // Użytkownik musi mieć > 0 punktów, by dodawać zgłoszenia/inicjatywy
    ],

];