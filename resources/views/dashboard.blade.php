<x-app-layout>
    <x-slot name="logo">
        <x-app-logo class="h-20 w-auto mx-auto" />
    </x-slot>
    {{-- Slot del título de la página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            RxDigital – Recetas médicas digitales seguras
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensajes de estado (éxito, etc.) --}}
            @if (session('status'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Botón para generar nueva receta --}}
            <div class="flex justify-end">
                <a href="{{ route('prescriptions.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm mr-3 sm:mr-0">
                    {{-- Ícono + --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Generar nueva receta
                </a>
            </div>

            {{-- Grid con las 4 columnas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mx-3 sm:mr-0">

                {{-- Columna: Recetas pendientes --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-amber-400 mr-2"></span>
                        Recetas pendientes
                    </h3>
                    <p class="text-xs text-gray-500 mb-3">
                        Enviadas al paciente, esperando que complete sus datos.
                    </p>

                    <div class="space-y-3 overflow-y-auto max-h-80">
                        @forelse ($pending as $prescription)
                            @php
                                // Link público que eventualmente usará el paciente
                                $shareUrl = config('app.url') . '/r/' . $prescription->public_token;
                            @endphp

                            <div class="border border-gray-100 rounded-lg p-3">
                                <div class="text-xs text-gray-400">
                                    Creada: {{ $prescription->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="text-sm text-gray-800 font-medium mt-1 line-clamp-2">
                                    {{ Str::limit($prescription->rp, 80) }}
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                                    {{-- Botón copiar link (por ahora solo muestra el enlace) --}}
                                    <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $shareUrl }}')"
                                        class="px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-md border border-blue-100">
                                        Copiar link
                                    </button>

                                    {{-- Botón enviar por WhatsApp --}}
                                    @php
                                        $waText = urlencode(
                                            "Hola, te envío el enlace para completar tus datos de la receta médica:\n{$shareUrl}",
                                        );
                                        $waUrl = "https://wa.me/?text={$waText}";
                                    @endphp
                                    <a href="{{ $waUrl }}" target="_blank"
                                        class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-md border border-emerald-100">
                                        Enviar por WhatsApp
                                    </a>

                                    {{-- Botón cancelar receta --}}
                                    <form action="{{ route('prescriptions.cancel', $prescription) }}" method="POST"
                                        onsubmit="return confirm('¿Cancelar esta receta? Esta acción no se puede deshacer.');">
                                        @csrf
                                        <button type="submit"
                                            class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded-md border border-red-100">
                                            Cancelar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No hay recetas pendientes.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Columna: Recetas recibidas --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-blue-400 mr-2"></span>
                        Recetas recibidas
                    </h3>
                    <p class="text-xs text-gray-500 mb-3">
                        El paciente ya completó sus datos. Listas para generar la receta.
                    </p>

                    <div class="space-y-3 overflow-y-auto max-h-80">
                        @forelse ($received as $prescription)
                            <div class="border border-gray-100 rounded-lg p-3">
                                <div class="text-xs text-gray-400">
                                    Recibida: {{ $prescription->updated_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="text-sm text-gray-800 font-medium">
                                    {{-- Nombre del paciente si ya lo tiene cargado --}}
                                    @if ($prescription->patient_first_name)
                                        {{ $prescription->patient_first_name }} {{ $prescription->patient_last_name }}
                                    @else
                                        Paciente sin nombre (datos incompletos)
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-1 line-clamp-2">
                                    {{ Str::limit($prescription->rp, 80) }}
                                </div>

                                <div class="mt-3">
                                    {{-- Botón Generar receta  --}}
                                    <form action="{{ route('prescriptions.generate', $prescription) }}" method="POST"
                                        onsubmit="return confirm('¿Generar la receta definitiva? Luego no se podrá modificar.');">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md">
                                            Generar receta
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No hay recetas recibidas.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Columna: Recetas finalizadas --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                        Recetas finalizadas
                    </h3>
                    <p class="text-xs text-gray-500 mb-3">
                        Recetas emitidas. Podés ver o descargar la receta.
                    </p>

                    <div class="space-y-3 overflow-y-auto max-h-80">
                        @forelse ($finalized as $prescription)
                            <div class="border border-gray-100 rounded-lg p-3">
                                <div class="text-xs text-gray-400">
                                    Emitida:
                                    {{ optional($prescription->issued_at)->format('d/m/Y H:i') ?? 'Sin fecha' }}
                                </div>
                                <div class="text-sm text-gray-800 font-medium">
                                    @if ($prescription->patient_first_name)
                                        {{ $prescription->patient_first_name }} {{ $prescription->patient_last_name }}
                                    @else
                                        Paciente sin nombre
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-1 line-clamp-2">
                                    {{ Str::limit($prescription->rp, 80) }}
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                                    @if ($prescription->pdf_path)
                                        <a href="{{ route('prescriptions.pdf.view', $prescription) }}"
                                            class="px-2 py-1 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-md border border-gray-200">
                                            Ver receta
                                        </a>
                                        <a href="{{ route('prescriptions.pdf.download', $prescription) }}"
                                            class="px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-md border border-blue-100">
                                            Descargar receta
                                        </a>
                                    @else
                                        <span class="text-gray-400">PDF no generado</span>
                                    @endif
                                    {{-- Botón para borrar receta finalizada --}}
                                    <form method="POST" action="{{ route('prescriptions.destroy', $prescription) }}"
                                        class="inline"
                                        onsubmit="return confirm('¿Seguro que querés borrar esta receta? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 rounded-md border border-red-200">
                                            Borrar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No hay recetas finalizadas.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Columna: Recetas canceladas --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                        Recetas canceladas
                    </h3>
                    <p class="text-xs text-gray-500 mb-3">
                        Recetas anuladas antes de que el paciente completara sus datos.
                    </p>

                    <div class="space-y-3 overflow-y-auto max-h-80">
                        @forelse ($cancelled as $prescription)
                            <div class="border border-gray-100 rounded-lg p-3">
                                <div class="text-xs text-gray-400">
                                    Cancelada: {{ $prescription->updated_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="text-xs text-gray-600 mt-1 line-clamp-2">
                                    {{ Str::limit($prescription->rp, 80) }}
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">No hay recetas canceladas.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
