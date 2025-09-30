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
        Schema::create('contract', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained()->onDelete('cascade');
            $table->string('contract_type'); // Recording, Publishing, Distribution, etc.
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('terms')->nullable();
            $table->decimal('advance_amount', 12, 2)->nullable();
            $table->decimal('royalty_percentage', 5, 2)->nullable(); // Artist royalty percentage
            $table->integer('minimum_albums')->nullable();
            $table->enum('status', ['active', 'expired', 'terminated', 'pending'])->default('pending');
            $table->string('contract_file_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract');
    }
};
