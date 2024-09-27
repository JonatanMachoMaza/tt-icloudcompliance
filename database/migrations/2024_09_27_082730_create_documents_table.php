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
		Schema::create('documents', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->text('description');
			$table->enum('relevancia', ['Alta', 'Media', 'Baja']);
			$table->date('fecha_aprobacion');
			$table->string('path');
			$table->string('file_hash')->nullable(); // Campo para almacenar el hash del archivo
			$table->unsignedBigInteger('user_id');
			$table->boolean('aprobado')->default(false); // Campo para rastrear si está aprobado o no
			$table->unsignedBigInteger('aprobado_por')->nullable(); // ID del usuario que aprobó el documento
			$table->unsignedBigInteger('revisado_por')->nullable(); // ID del usuario que rechazó el documento
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('aprobado_por')->references('id')->on('users')->onDelete('set null');
			$table->foreign('revisado_por')->references('id')->on('users')->onDelete('set null');
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
