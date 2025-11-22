<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use Carbon\Carbon;
use App\Notifications\PrescriptionCompletedNotification;


class PublicPrescriptionController extends Controller
{
    //Muestra el formulario para que el paciente complete sus datos

    public function showPatientForm(string $token){
        //busca la receta con el token valido
        $prescription = Prescription::where('public_token', $token)->first();

        //si no existe o ya fue entregada, muestra error
        if (!$prescription){
            abort(404, 'Enlace de receta no valido.');
        }

        //solo se puede completa si la receta esta en estado pendiente
        if ($prescription->status !== 'sent_to_patient'){
            abort(403, 'La receta no puede ser completada.');
        }

        //cargamos tambien los datos del medico o usuario
        $doctor = $prescription->user;

        return view('prescriptions.patient_form', compact('prescription', 'doctor'));
    }

    //Procesa el formulario completado por el paciente

    public function submitPatientForm(Request $request, string $token){
        //busca la receta con el token valido
        $prescription = Prescription::where('public_token', $token)->first();

        if (!$prescription){
            abort(404, 'Enlace de receta no valido.');
        }

        //verificamos que todavia esta en estado pendiente
        if ($prescription->status !== 'sent_to_patient'){
            abort(403, 'La receta no puede ser completada.');
        }

        //validamos los datos del paciente
        $data = $request->validate([
            'patient_first_name' => ['required', 'string', 'min:2', 'max:30', 'regex:/^[\pL\s\'\-]+$/u'],// Solo letras, espacios y guiones/apóstrofes
            'patient_last_name'  => ['required', 'string', 'min:2', 'max:30', 'regex:/^[\pL\s\'\-]+$/u'],
            'patient_birth_date' => ['required', 'date', 'before_or_equal:today', 'after:1900-01-01'],
            'patient_dni'        => ['required', 'digits_between:7,10'],
            'patient_health_insurance' => ['nullable', 'string', 'max:100'],
        ], [
            'patient_first_name.required' => 'El nombre es obligatorio.',
            'patient_first_name.min'      => 'El nombre debe tener al menos 2 caracteres.',
            'patient_first_name.regex'    => 'El nombre solo puede contener letras.',
            'patient_last_name.string'    => 'El apellido debe ser una cadena de texto.',
            'patient_last_name.required'  => 'El apellido es obligatorio.',
            'patient_last_name.min'       => 'El apellido debe tener al menos 2 caracteres.',
            'patient_last_name.regex'     => 'El apellido solo puede contener letras.',
            'patient_birth_date.string'  => 'La fecha de nacimiento debe ser una cadena de texto.',
            'patient_birth_date.required' => 'La fecha de nacimiento es obligatoria.',
            'patient_birth_date.date'     => 'La fecha de nacimiento no es válida.',
            'patient_birth_date.before_or_equal' => 'La fecha de nacimiento debe ser anterior o igual    a hoy.',
            'patient_birth_date.after'    => 'La fecha de nacimiento debe ser posterior a 1900.',
            'patient_dni.required'        => 'El DNI es obligatorio.',
            'patient_dni.digits_between'  => 'El DNI debe tener entre 7 y 10 dígitos.']);

        //calculamos la edad del paciente segun la fecha de nacimiento
        $birthDate = Carbon::parse($data['patient_birth_date']);
        $age = $birthDate->age;

        //actualizamos los datos del paciente en la receta
        $prescription->patient_first_name = $data['patient_first_name'];
        $prescription->patient_last_name = $data['patient_last_name'];
        $prescription->patient_birth_date = $data['patient_birth_date'];
        $prescription->patient_age = $age;
        $prescription->patient_dni = $data['patient_dni'];
        $prescription->patient_health_insurance = $data['patient_health_insurance'] ?? null;

        //cambiamos el estado de la receta
        $prescription->status = 'completed';
        $prescription->save();

        //notificamos al medico que la receta fue completada por el paciente
        $doctor = $prescription->user;
        if ($doctor && $doctor->email){
            $doctor->notify(new PrescriptionCompletedNotification($prescription));
        }

        //vista de confirmacion
        return view('prescriptions.patient_thanks', ['prescription' => $prescription]);
    }

    public function verify(string $token){
        $prescription = Prescription::where('public_token', $token)->first();

        if (! $prescription) {
            abort(404, 'Receta no encontrada.');
        }

        // Solo mostrar si está finalizada
        if ($prescription->status !== 'finalized') {
            abort(403, 'Esta receta aún no ha sido emitida por el profesional.');
        }

        $doctor = $prescription->user;

        return view('prescriptions.verify', compact('prescription', 'doctor'));
    }

}
