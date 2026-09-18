<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('module_order_items', function (Blueprint $table) {
    $table->id();

    $table->foreignId('module_order_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->string('module');

    $table->unsignedInteger('amount');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_order_items');
    }
};
