<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->default('user')->after('password'); // user, business_owner, employee
            $table->foreignId('current_business_id')->nullable()->after('user_type')->constrained('businesses')->onDelete('set null');
            $table->string('subdomain')->nullable()->unique()->after('current_business_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'current_business_id', 'subdomain']);
        });
    }
};
