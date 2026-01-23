<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('type'); // 'comment', 'vote_up', 'vote_down'
            $table->string('notifiable_type'); // Article, Offer, Todo, Poll, etc.
            $table->unsignedBigInteger('notifiable_id');
            $table->text('message');
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
