<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('subdomain')->nullable()->unique()->after('slug');
            $table->json('business_hours')->nullable()->after('website'); // {'mon': {'open': '09:00', 'close': '17:00'}, ...}
            $table->integer('booking_slot_duration')->default(30)->after('business_hours'); // w minutach
            $table->boolean('is_approved')->default(false)->after('booking_slot_duration');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn(['subdomain', 'business_hours', 'booking_slot_duration', 'is_approved']);
        });
    }
};
