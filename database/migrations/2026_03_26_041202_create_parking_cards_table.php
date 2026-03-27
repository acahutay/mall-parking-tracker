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
            $table->foreignId('parking_section_id')->constrained('parking_sections');
            $table->string('plate_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();
            $table->index(['parking_section_id', 'is_active']);
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
