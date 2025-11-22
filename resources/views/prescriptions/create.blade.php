<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Generar nueva receta
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl sm:px-6 lg:px-8 mx-3 sm:mr-0">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">

                <p class="text-sm text-gray-600 mb-4">
                    Completá el <strong>RP / medicación</strong> y las notas que quieras incluir.
                    Luego la receta quedará en estado <strong>pendiente</strong> y podrás enviarle
                    un enlace al paciente para que complete sus datos personales.
                </p>

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

                {{-- Formulario --}}
                <form method="POST" action="{{ route('prescriptions.store') }}" class="space-y-4">
                    @csrf

                    {{-- Campo RP --}}
                    <div>
                        <label for="rp" class="block text-sm font-medium text-gray-700 mb-1">
                            RP / Medicación / Indicaciones
                        </label>
                        <textarea id="rp" name="rp" rows="6" required
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('rp') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            Ejemplo: DUTIDE, Elea – semaglutida 1 mg/jeringa prellenada, 1 inyección subcutánea 1 vez por semana, etc.
                        </p>
                    </div>

                    {{-- Campo Notas (opcional) --}}
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                            Notas (opcional)
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('notes') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            Aquí podés agregar recomendaciones generales, recordatorios, etc.
                        </p>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('dashboard') }}"
                           class="text-sm text-gray-600 hover:text-gray-800">
                            ← Volver al panel
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm">
                            Guardar receta pendiente
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
