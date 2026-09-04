<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('resource_id')->nullable()->after('service_id')->constrained('resources')->nullOnDelete();
            $table->index(['resource_id', 'start_time', 'end_time']);
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['resource_id']);
            $table->dropIndex(['resource_id', 'start_time', 'end_time']);
            $table->dropColumn('resource_id');
        });
    }
};