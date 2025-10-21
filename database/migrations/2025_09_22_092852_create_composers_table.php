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
        Schema::create('composers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);  // e.g., "Ludwig van Beethoven"
            $table->string('nationality', 100)->nullable();  // e.g., "German"
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('gender', 10)->nullable(); // e.g., '
            $table->text('bio')->nullable();  // Short biography
            $table->string('image_url')->nullable();  // Optional profile image
            $table->timestamps();  // created_at, updated_at
            // Indexes for performance
            $table->index('name');
            $table->index('nationality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composers');
    }
};
