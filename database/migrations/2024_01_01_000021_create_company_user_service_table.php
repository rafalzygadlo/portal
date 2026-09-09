<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Which services a given employee (company_user) is able to perform.
     */
    public function up(): void
    {
        Schema::create('company_user_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_user_id')->constrained('company_user')->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['company_user_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_user_service');
    }
};
