<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('type');
        });

        Schema::table('resource_bookings', function (Blueprint $table) {
            $table->json('resource_ids')->nullable()->after('resource_id');
            $table->decimal('total_price', 10, 2)->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('resource_bookings', function (Blueprint $table) {
            $table->dropColumn(['resource_ids', 'total_price']);
        });
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('hourly_rate');
        });
    }
};