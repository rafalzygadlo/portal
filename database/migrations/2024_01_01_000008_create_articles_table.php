<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('slug')->unique();
            $table->softDeletes();
            $table->timestamps();
            $table->string('deletion_reason')->nullable()->comment('spam, admin_deleted, reported, etc.');

            $table->index('title');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
