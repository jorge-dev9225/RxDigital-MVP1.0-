<x-guest-layout>
    <x-slot name="logo">
        <x-thanks-logo class="h-16 w-auto mx-auto" />
    </x-slot>
    <div class="min-h-[80vh] flex flex-col items-center bg-gray-50 px-4 py-6">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-md border border-gray-200 p-5 sm:p-6">
            {{-- Encabezado con datos del médico --}}
            <div class="mb-4 border-b border-gray-100 pb-3">
                <h1 class="text-xl font-semibold text-gray-800">
                    Datos para receta médica
                </h1>
                <p class="mt-1 text-sm text-gray-600">
                    Estás completando tus datos personales para una receta de:
                </p>
                <p class="mt-1 text-sm font-medium text-gray-800">
                    Dra. {{ $doctor->name ?? 'Médico' }}
                </p>
                @if($doctor->license_number)
                    <p class="text-xs text-gray-500">
                        Matrícula: {{ $doctor->license_number }}
                    </p>
                @endif
                @if($doctor->specialty)
                    <p class="text-xs text-gray-500">
                        Especialidad: {{ $doctor->specialty }}
                    </p>
                @endif
            </div>

            {{-- Breve resumen del RP (sin mostrar todo si es muy largo) --}}
            <div class="mb-4 bg-blue-50 border border-blue-100 rounded-lg px-3 py-2">
                <p class="text-xs font-semibold text-blue-800 mb-1">
                    Resumen de la indicación médica (RP):
                </p>
                <p class="text-xs text-blue-900">
                    {{ \Illuminate\Support\Str::limit($prescription->rp, 160) }}
                </p>
            </div>

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="mb-4">
                    <div class="text-sm text-red-700 bg-red-50 border border-red-200 px-3 py-2 rounded-lg">
                        <p class="font-semibold mb-1">Revisá los siguientes errores:</p>
                        <ul class="list-disc list-inside text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Formulario de datos del paciente --}}
            <form method="POST" action="{{ route('prescriptions.patient.submit', $prescription->public_token) }}" class="space-y-4">
                @csrf

                <div>
                    <label for="patient_first_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre
                    </label>
                    <input type="text" id="patient_first_name" name="patient_first_name"
                           value="{{ old('patient_first_name') }}"
                           required
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <div>
                    <label for="patient_last_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Apellido
                    </label>
                    <input type="text" id="patient_last_name" name="patient_last_name"
                           value="{{ old('patient_last_name') }}"
                           required
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <div>
                    <label for="patient_birth_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Fecha de nacimiento
                    </label>
                    <input type="date" id="patient_birth_date" name="patient_birth_date"
                           value="{{ old('patient_birth_date') }}"
                           required
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <div>
                    <label for="patient_dni" class="block text-sm font-medium text-gray-700 mb-1">
                        Documento de identidad (DNI)
                    </label>
                    <input type="text" id="patient_dni" name="patient_dni"
                           value="{{ old('patient_dni') }}"
                           required
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <div>
                    <label for="patient_health_insurance" class="block text-sm font-medium text-gray-700 mb-1">
                        Obra social (opcional)
                    </label>
                    <input type="text" id="patient_health_insurance" name="patient_health_insurance"
                           value="{{ old('patient_health_insurance') }}"
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                           placeholder="Si no tenés, podés dejarlo en blanco">
                </div>

                <div class="mt-4">
                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm">
                        Enviar datos al médico
                    </button>
                </div>
            </form>

            <p class="mt-4 text-[11px] text-gray-400 text-center">
                Al enviar tus datos, autorizás al profesional médico a utilizarlos para la emisión de tu receta.
            </p>
        </div>
    </div>
</x-guest-layout>
