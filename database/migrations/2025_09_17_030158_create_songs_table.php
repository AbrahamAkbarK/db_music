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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('album_id')->constrained()->onDelete('set null');
            $table->foreignId('artist_id')->constrained()->onDelete('cascade');
            $table->foreignId('composer_id')->constrained('composers')->default(1)->onDelete('cascade');
            $table->integer('track_number')->nullable();
            $table->integer('duration_seconds')->nullable(); // Duration in seconds
            $table->string('genre')->nullable();
            $table->text('lyrics')->nullable();
            $table->string('isrc_code')->unique()->nullable(); // International Standard Recording Code
            $table->string('audio_file_path')->nullable();
            $table->string('demo_file_path')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->enum('status', ['draft', 'recorded', 'mixed', 'mastered', 'released'])->default('draft');
            $table->string('composer')->nullable();
            $table->string('lyricist')->nullable();
            $table->string('arranger')->nullable();
            $table->string('royalty_contract')->nullable();
            $table->string('label')->default('PT Aquarius Musikindo');
            $table->boolean('is_explicit')->default(false);

            $table->timestamps();

            $table->unique(['album_id','artist_id', 'track_number','composer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
