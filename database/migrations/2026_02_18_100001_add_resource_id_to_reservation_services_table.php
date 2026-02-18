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
        Schema::table('reservation_services', function (Blueprint $table) {
            $table->foreignId('resource_id')->after('business_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservation_services', function (Blueprint $table) {
            $table->dropForeign(['resource_id']);
            $table->dropColumn('resource_id');
        });
    }
};
