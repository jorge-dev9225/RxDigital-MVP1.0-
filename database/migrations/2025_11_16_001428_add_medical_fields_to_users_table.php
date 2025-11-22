<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agregamos todos los campos medicos a la tabla users
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //matricula medica
            $table->string('license_number')->nullable()->after('email');
            //especialidad medica
            $table->string('specialty')->nullable()->after('license_number');
            //direccion consultorio o institucion
            $table->string('clinic_address')->nullable()->after('specialty');

        });
    }

    /**
     * En caso de hacer rollback se eliminan los campos agregados
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'license_number',
                'specialty',
                'clinic_address'
            ]);
        });
    }
};
