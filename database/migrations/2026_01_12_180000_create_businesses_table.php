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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();;
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('is_claimed')->default(false);
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            
            $table->string('subdomain')->unique();
            $table->json('business_hours')->nullable(); // {'mon': {'open': '09:00', 'close': '17:00'}, ...}
            $table->string('deletion_reason')->nullable()->comment('spam, admin_deleted, reported, etc.');
            
            
            $table->timestamps();
            $table->softDeletes();




            $table->index('name');
            $table->index('subdomain');
            $table->index('email');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
