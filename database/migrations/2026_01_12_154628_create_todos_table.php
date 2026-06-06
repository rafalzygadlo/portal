<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique();
            $table->enum('status', ['pending', 'planned', 'completed'])->default('pending');
            $table->string('deletion_reason')->nullable()->comment('spam, admin_deleted, reported, etc.');
            $table->timestamps();
            $table->softDeletes();


            $table->index('user_id');
            $table->index('slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('todos');
    }
};
