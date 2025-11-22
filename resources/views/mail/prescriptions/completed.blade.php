@component('mail::message')
# Hola, {{ $doctorName }}

Un paciente acaba de completar sus datos para una receta en **RxDigital**.

**Resumen de la receta:**

- ID receta: **#{{ $prescription->id }}**
- Paciente: **{{ $prescription->patient_first_name }} {{ $prescription->patient_last_name }}**
- DNI: **{{ $prescription->patient_dni ?? '—' }}**
- Obra social: **{{ $prescription->patient_health_insurance ?? 'Sin obra social' }}**

Podés revisar y emitir la receta desde tu panel:

@component('mail::button', ['url' => route('dashboard')])
Ir al panel de RxDigital
@endcomponent

Gracias por utilizar **RxDigital – Recetas médicas digitales seguras**.

@endcomponent
