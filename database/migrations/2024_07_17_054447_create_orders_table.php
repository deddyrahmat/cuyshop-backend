<?php

use App\Models\Address;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
            $table->string('snap_url')->nullable();
            $table->json('order_items')->nullable();
            $table->foreignIdFor(Address::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignIdFor(User::class, 'updated_by')->nullable()->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
