<div>
    <x-slot name="title">Cambiar Estado - Ticket #{{ $ticket->id }}</x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-brand-primary">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-brand-primary md:ml-2">
                                Ticket #{{ $ticket->id }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Cambiar Estado</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Cambiar Estado del Ticket</h2>
                <p class="mt-1 text-sm text-gray-600">Ticket #{{ $ticket->id }} - {{ $ticket->titulo }}</p>
            </div>

            <!-- Información del Ticket -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Usuario</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $ticket->usuario->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Estado Actual</p>
                        <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @if($ticket->estado === App\Enums\EstadoTicket::PENDIENTE) bg-gray-100 text-gray-800
                            @elseif($ticket->estado === App\Enums\EstadoTicket::EN_PROCESO) bg-orange-100 text-brand-primary
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $ticket->estado->nombre() }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Prioridad</p>
                        <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @if($ticket->prioridad->value === 2) bg-red-100 text-red-800
                            @elseif($ticket->prioridad->value === 1) bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $ticket->prioridad->nombre() }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Categoría</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $ticket->categoria->nombre }}</p>
                    </div>
                </div>
            </div>

            <!-- Formulario de Cambio de Estado -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                @if (session()->has('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="cambiarEstado">
                    <div class="space-y-6">
                        <!-- Nuevo Estado -->
                        <div>
                            <label for="nuevoEstado" class="block text-sm font-semibold text-gray-900 mb-2">
                                Nuevo Estado <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="nuevoEstado"
                                name="nuevoEstado"
                                wire:model="nuevoEstado"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary focus:ring-opacity-50 transition"
                                required
                            >
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->value }}">
                                        {{ $estado->nombre() }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-gray-500">Selecciona el nuevo estado para el ticket</p>
                            @error('nuevoEstado')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Comentario -->
                        <div>
                            <label for="contenido" class="block text-sm font-semibold text-gray-900 mb-2">
                                Comentario (opcional)
                            </label>
                            <textarea 
                                id="contenido"
                                name="contenido"
                                wire:model="contenido"
                                rows="5"
                                maxlength="500"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary focus:ring-opacity-50 transition"
                                placeholder="Agrega un comentario sobre el cambio de estado... (opcional)"
                            ></textarea>
                            <div class="mt-2 flex items-center justify-between">
                                <p class="text-xs text-gray-500">Este comentario se guardará en el historial del ticket</p>
                                <p class="text-xs font-medium {{ strlen($contenido) > 450 ? 'text-red-600' : 'text-gray-500' }}">
                                    {{ strlen($contenido) }}/500
                                </p>
                            </div>
                            @error('contenido')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info adicional -->
                        <div class="bg-orange-50 border border-brand-primary rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-brand-primary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700">
                                        El cambio de estado se registrará en el historial y el usuario del ticket recibirá una notificación.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-6 flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                        <a 
                            href="{{ route('tickets.show', $ticket->id) }}"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                        >
                            Cancelar
                        </a>
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="inline-flex items-center px-6 py-2.5 bg-brand-primary hover:bg-brand-primary-dark text-white font-medium rounded-lg transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg wire:loading.remove class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove>Guardar Cambios</span>
                            <span wire:loading>Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
