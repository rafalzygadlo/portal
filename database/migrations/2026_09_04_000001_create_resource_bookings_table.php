<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->foreignId('resource_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('status')->default('confirmed');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['resource_id', 'start_time', 'end_time']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_bookings');
    }
};