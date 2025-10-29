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
       Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->text('content'); // Isi dari catatannya

            // Ini adalah kolom polimorfik
            // Akan membuat 'noteable_id' (INT) dan 'noteable_type' (VARCHAR)
            $table->morphs('noteable');

            // Opsional: untuk melacak siapa yang menambah catatan
            // $table->foreignId('user_id')->nullable()->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
