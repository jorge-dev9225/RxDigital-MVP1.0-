<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Creamos la tabla de recetas médicas.
     */
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            //ID autoincremental
            $table->id();
            //relacion con el usuario
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            //----------DATOS DEL PACIENTE----------
            
            //nombre del paciente
            $table->string('patient_first_name')->nullable();
            //apellido del paciente
            $table->string('patient_last_name')->nullable();
            //fecha de nacimiento del paciente
            $table->date('patient_birth_date')->nullable();
            //edad del paciente
            $table->unsignedSmallInteger('patient_age')->nullable();
            //decumento de identidad
            $table->string('patient_dni', 50)->nullable();
            //Obra social
            $table->string('patient_health_insurance')->nullable();

            //----------CONTENIDO MEDICO----------
            //textoo libre para la receta medica
            $table->text('rp');
            //notas adicionales
            $table->text('notes')->nullable();

            //----------ESTADO DE LA RECETA----------
            $table->enum('status', ['sent_to_patient', 'completed', 'finalized', 'cancelled'])->default('sent_to_patient');
            //verificacion de la receta
            $table->string('public_token')->unique();
            //fecha y hora de la emision de la receta
            $table->dateTime('issued_at')->nullable();
            //ruta del archivo PDF generado
            $table->string('pdf_path')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
