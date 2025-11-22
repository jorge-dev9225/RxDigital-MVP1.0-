<x-guest-layout>
    <x-slot name="logo">
        <x-thanks-logo class="h-24 w-auto mx-auto" />
    </x-slot>
    <div class="min-h-3/4 flex flex-col items-center justify-center bg-gray-50 px-4">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h1 class="text-xl font-semibold text-gray-800 mb-2">
                Verificación de receta médica
            </h1>
            <p class="text-sm text-gray-600 mb-4">
                Esta es una vista de solo lectura para verificar la autenticidad de la receta.
            </p>

            <div class="mb-4">
                <p class="text-sm text-gray-700">
                    <strong>Dra. {{ $doctor->name }}</strong><br>
                    @if($doctor->specialty)
                        Especialidad: {{ $doctor->specialty }}<br>
                    @endif
                    @if($doctor->license_number)
                        Matrícula: {{ $doctor->license_number }}<br>
                    @endif
                </p>
                <p class="text-xs text-gray-500">
                    Emitida: {{ optional($prescription->issued_at)->format('d/m/Y H:i') ?? 'Sin fecha' }}
                </p>
            </div>

            <div class="mb-4">
                <h2 class="text-sm font-semibold text-gray-800 mb-1">Datos del paciente</h2>
                <div class="text-sm text-gray-700">
                    Nombre: <strong>{{ $prescription->patient_first_name }} {{ $prescription->patient_last_name }}</strong><br>
                    DNI: {{ $prescription->patient_dni ?? '—' }}<br>
                    Fecha de nacimiento:
                    @if($prescription->patient_birth_date)
                        {{ $prescription->patient_birth_date->format('d/m/Y') }}
                    @else
                        —
                    @endif
                    <br>
                    Edad: {{ $prescription->patient_age ?? '—' }} años<br>
                    Obra social: {{ $prescription->patient_health_insurance ?? 'Sin obra social' }}
                </div>
            </div>

            <div class="mb-4">
                <h2 class="text-sm font-semibold text-gray-800 mb-1">RP / Indicación médica</h2>
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 text-sm text-gray-800 whitespace-pre-line">
                    {{ $prescription->rp }}
                </div>
            </div>

            @if($prescription->notes)
                <div class="mb-4">
                    <h2 class="text-sm font-semibold text-gray-800 mb-1">Notas / indicaciones adicionales</h2>
                    <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 text-sm text-gray-800 whitespace-pre-line">
                        {{ $prescription->notes }}
                    </div>
                </div>
            @endif

            <p class="mt-4 text-[11px] text-gray-400 text-center">
                Esta página es solo para verificación. No permite descargar el documento original.<br>
                Sistema provisto por <strong>RxDigital</strong>.
            </p>
        </div>
    </div>
</x-guest-layout>
