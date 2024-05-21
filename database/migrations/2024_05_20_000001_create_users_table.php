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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('profile_id');
            $table->string('avatar')->unique()->nullable();
            $table->string('name', 50);
            $table->string('username', 15)->unique();
            $table->string('phone', 12)->unique()->nullable();
            $table->string('email',50)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
