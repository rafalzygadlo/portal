<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('credits')->default(0)->after('status');
            $table->string('referral_code')->nullable()->unique()->after('credits');
            $table->foreignId('referred_by_user_id')->nullable()->constrained('users')->nullOnDelete()->after('referral_code');
            $table->boolean('welcome_bonus_received')->default(false)->after('referred_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referred_by_user_id');
            $table->dropColumn(['referral_code', 'credits', 'welcome_bonus_received']);
        });
    }
};
