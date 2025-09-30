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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->morphs('sellable'); // Can be album or song
            $table->string('platform'); // Spotify, Apple Music, Amazon, etc.
            $table->integer('quantity_sold');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('artist_royalty', 10, 2)->nullable();
            $table->decimal('label_commission', 10, 2)->nullable();
            $table->date('sale_date');
            $table->string('territory')->nullable(); // Country/region
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
