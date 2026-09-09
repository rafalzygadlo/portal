<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            // This is the polymorphic key: creates `promotable_id` and `promotable_type` columns.
            $table->morphs('promotable');
            $table->timestamp('expires_at')->nullable(); // when the promotion expires
            $table->timestamps();

            $table->index(['promotable_id', 'promotable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
