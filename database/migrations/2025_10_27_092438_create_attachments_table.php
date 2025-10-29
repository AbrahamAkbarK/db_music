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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            // Kolom polimorfik
            $table->morphs('attachable');

            $table->string('original_filename'); // Nama file asli (cth: "lirik.pdf")
            $table->string('storage_path');      // Path di server (cth: "attachments/xyz.pdf")
            $table->string('file_type')->nullable(); // cth: "pdf", "jpg"
            $table->unsignedBigInteger('file_size')->nullable(); // Ukuran file dalam bytes

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
