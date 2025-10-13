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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship to link this contract to a Song (or other models)
            $table->morphs('contractable');

            // Your specified fields
            $table->string('contract_number')->unique();
            $table->string('contract_type');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('status'); // e.g., 'draft', 'active', 'expired'
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
