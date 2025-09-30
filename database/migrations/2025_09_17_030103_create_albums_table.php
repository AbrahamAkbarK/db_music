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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('genre')->nullable();
            $table->string('subgenre')->nullable();
            $table->date('release_date');
            $table->enum('type', ['album', 'ep', 'single', 'compilation'])->default('album');
            $table->string('cover_image')->nullable();
            $table->string('upc_code')->unique()->nullable(); // Universal Product Code
            $table->decimal('price', 8, 2)->nullable();
            $table->enum('status', ['draft', 'scheduled', 'released', 'archived'])->default('draft');
            $table->integer('total_tracks')->default(0);
            $table->integer('duration_seconds')->default(0); // Total duration in seconds
            $table->string('producer')->nullable();
            $table->string('record_label')->nullable();
            $table->string('recording_studio')->nullable();
            $table->year('recording_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
