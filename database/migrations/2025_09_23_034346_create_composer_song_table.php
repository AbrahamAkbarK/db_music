<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::create('composer_song', function (Blueprint $table) {
            $table->id();
            $table->foreignId('composer_id')->constrained('composers')->onDelete('cascade');
            $table->foreignId('song_id')->constrained('songs')->onDelete('cascade');
            $table->string('role')->nullable();  // e.g., 'primary_composer', 'arranger'
            $table->timestamps();  // created_at, updated_at for pivot
            // Unique constraint: Prevent duplicate links (one composer per song, or allow multiples if needed)
            $table->unique(['composer_id', 'song_id']);
            // Indexes for performance (on foreign keys)
            $table->index('composer_id');
            $table->index('song_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composer_song');
    }
};
