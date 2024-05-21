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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('description', 100);
            $table->boolean('status')->unsigned()->default(false);
            $table->string('roles', 500)->nullable();
            $table->timestamps();

        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 30);
            $table->string('action', 15);
            $table->timestamps();
        });

        Schema::create('roles_profiles', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('profile_id');

            $table->primary(['role_id', 'profile_id']);
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('roles_profiles');
    }
};
