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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('ratable'); // Akan membuat ratable_id dan ratable_type
            $table->integer('rating')->comment('1-5');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Memastikan user hanya bisa memberi satu rating per rental per item
            $table->unique(['rental_id', 'user_id', 'ratable_id', 'ratable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
}; 