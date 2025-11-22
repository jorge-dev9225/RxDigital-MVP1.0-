<x-guest-layout>
    <x-slot name="logo">
        <x-thanks-logo class="h-24 w-auto mx-auto" />
    </x-slot>
    <div class="min-h-[30vh] flex flex-col items-center bg-gray-50 px-4 py-6">
        <div class="w-full max-w-md bg-white rounded-xl shadow-md border border-gray-200 p-5 sm:p-6">
            <h1 class="text-xl font-semibold text-gray-800 mb-2">
                ¡Gracias! 🙌
            </h1>
            <p class="text-sm text-gray-600 mb-4">
                Tus datos han sido enviados correctamente al médico.
            </p>

            <p class="text-sm text-gray-700 mb-4">
                En breve el profesional emitirá tu receta.
            </p>
            <p class="text-xs text-gray-400 mt-4">
                Receta gestionada a través de <strong>RxDigital</strong> – Recetas médicas digitales seguras.
            </p>
            

            @if($prescription->user)
                <p class="text-xs text-gray-500">
                    Profesional: {{ $prescription->user->name }}
                    @if($prescription->user->license_number)
                        · Matrícula: {{ $prescription->user->license_number }}
                    @endif
                </p>
            @endif
        </div>
    </div>
</x-guest-layout>
