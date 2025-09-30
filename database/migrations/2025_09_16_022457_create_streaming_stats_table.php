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
        Schema::create('streaming_stats', function (Blueprint $table) {
            $table->id();
            $table->morphs('streamable'); // Can be album or song
            $table->string('platform'); // Spotify, Apple Music, YouTube, etc.
            $table->bigInteger('play_count')->default(0);
            $table->bigInteger('unique_listeners')->default(0);
            $table->decimal('revenue_generated', 10, 4)->default(0);
            $table->string('territory')->nullable();
            $table->date('stats_date');
            $table->timestamps();

            $table->unique(['streamable_id', 'streamable_type', 'platform', 'stats_date', 'territory']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('streaming_stats');
    }
};
