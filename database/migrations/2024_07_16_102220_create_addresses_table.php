<?php

use App\Models\City;
use App\Models\Province;
use App\Models\User;
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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('fullname', 255)->nullable();
            $table->string('phone', 12)->nullable();
            $table->string('address', 255);
            $table->foreignIdFor(Province::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignIdFor(City::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            // $table->string('district', 255);
            // $table->string('zipcode', 6);
            $table->string('other', 255)->nullable();
            $table->boolean('main')->default(0);
            $table->enum('location', ['home', 'office'])->default('home');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
