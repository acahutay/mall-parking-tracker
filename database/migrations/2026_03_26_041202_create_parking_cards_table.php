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
        Schema::create('parking_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parking_section_id');
            $table->string('plate_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_cards');
    }
};
