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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('stage_name')->nullable();
            $table->text('biography')->nullable();
            $table->string('genre')->nullable();
            $table->string('country')->nullable();
            $table->date('birth_date')->nullable()->format('Y-m-d');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('category')->nullable();
            $table->string('manager')->nullable();
            $table->string('website')->nullable();
            $table->string('spotify_url')->nullable();
            $table->string('apple_music_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('profile_image')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_hold'])->default('active');
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
