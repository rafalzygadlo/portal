<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            // en: This is the polymorphic key: creates `promotable_id` and `promotable_type` columns.
            // de: Dies ist der polymorphe Schlüssel: erstellt die Spalten `promotable_id` und `promotable_type`.
            $table->morphs('promotable');
            $table->timestamp('expires_at')->nullable(); // Kiedy promocja wygasa
            $table->timestamps();

            $table->index(['promotable_id', 'promotable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};