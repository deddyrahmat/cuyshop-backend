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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->boolean('main')->default(0);
            $table->string('fullname', 255);
            $table->string('phone', 12);
            $table->string('address', 255);
            $table->string('province', 255);
            $table->string('city', 255);
            $table->string('district', 255);
            $table->string('zipcode', 6);
            $table->string('other', 255);
            $table->enum('location', ['home', 'office'])->default('home');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_adresses');
    }
};
