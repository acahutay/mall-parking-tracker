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
        Schema::create('parking_spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_lot_id')->constrained()->cascadeOnDelete();
            $table->string('spot_number');
            $table->enum('type', ['regular', 'handicap', 'electric'])->default('regular');
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->unique(['parking_lot_id', 'spot_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_spots');
    }
};
