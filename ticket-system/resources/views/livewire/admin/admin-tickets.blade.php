<div>
    <x-slot name="title">Gestión de Tickets</x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb y Header -->
            <div class="mb-6">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-brand-primary">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Gestión de Tickets</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Gestión de Tickets</h2>
                        <p class="mt-1 text-sm text-gray-600">Administra todos los tickets del sistema</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        Total: <span class="font-semibold text-gray-900">{{ $tickets->total() }}</span> tickets
                    </div>
                </div>
            </div>

            <!-- Alertas -->
            @if ($notification)
                <div class="fixed top-20 right-4 z-50 max-w-md" 
                     x-data="{ show: true }" 
                     x-show="show"
                     x-init="setTimeout(() => { show = false; $wire.set('notification', null); }, 5000)">
                    <x-toast type="{{ $notificationType }}">
                        {{ $notification }}
                    </x-toast>
                </div>
            @endif

            <!-- Filtros y Búsqueda -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Filtros</h3>
                    @if($search || $estadoFilter !== '' || $prioridadFilter !== '' || $categoriaFilter)
                        <button 
                            wire:click="$set('search', ''); $set('estadoFilter', ''); $set('prioridadFilter', ''); $set('categoriaFilter', '');"
                            class="text-sm text-brand-primary hover:text-brand-primary-dark font-medium">
                            Limpiar filtros
                        </button>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Búsqueda -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar
                        </label>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Título, descripción o usuario..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary"
                        >
                    </div>

                    <!-- Filtro Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Estado
                        </label>
                        <select 
                            wire:model.live="estadoFilter"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary"
                        >
                            <option value="">Todos los estados</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->value }}">{{ $estado->nombre() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Prioridad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Prioridad
                        </label>
                        <select 
                            wire:model.live="prioridadFilter"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary"
                        >
                            <option value="">Todas las prioridades</option>
                            @foreach($prioridades as $prioridad)
                                <option value="{{ $prioridad->value }}">{{ $prioridad->nombre() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Categoría -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Categoría
                        </label>
                        <select 
                            wire:model.live="categoriaFilter"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary"
                        >
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Tabla de Tickets -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ticket
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Usuario
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Categoría
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Prioridad
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $ticket->titulo }}
                                        </div>
                                        <div class="text-sm text-gray-500 truncate max-w-xs">
                                            {{ Str::limit($ticket->descripcion, 60) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $ticket->usuario->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $ticket->usuario->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $ticket->categoria->nombre }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($ticket->prioridad->value === 2) bg-red-100 text-red-800
                                            @elseif($ticket->prioridad->value === 1) bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ $ticket->prioridad->nombre() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($ticket->estado === App\Enums\EstadoTicket::PENDIENTE) bg-gray-100 text-gray-800
                                            @elseif($ticket->estado === App\Enums\EstadoTicket::EN_PROCESO) bg-orange-100 text-brand-primary
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ $ticket->estado->nombre() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $ticket->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-brand-primary hover:bg-brand-primary-dark text-white text-xs font-medium rounded-lg transition-colors duration-150 shadow-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mt-2">No se encontraron tickets</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Cambio de Estado (Inline) -->
    @if($showModal)
        <div class="bg-white border-t-4 border-brand-primary shadow-lg rounded-lg p-6 mb-6">
            <form wire:submit.prevent="cambiarEstado">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Cambiar Estado del Ticket
                    </h3>
                    <button 
                        type="button"
                        wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="space-y-5">
                                <!-- Estado -->
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
                                    <p class="mt-1 text-xs text-gray-500">Selecciona el nuevo estado para el ticket</p>
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
                                        rows="4"
                                        maxlength="500"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary focus:ring-opacity-50 transition"
                                        placeholder="Agrega un comentario sobre el cambio de estado... (opcional)"
                                    ></textarea>
                                    <div class="mt-1 flex items-center justify-between">
                                        <p class="text-xs text-gray-500">Este comentario se guardará en el historial</p>
                                        <p class="text-xs font-medium {{ strlen($contenido) > 450 ? 'text-red-600' : 'text-gray-500' }}">
                                            {{ strlen($contenido) }}/500
                                        </p>
                                    </div>
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
                                                El cambio de estado se registrará en el historial y el usuario del ticket será notificado.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <button 
                        type="button"
                        wire:click="closeModal"
                        class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                    >
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-brand-primary hover:bg-brand-primary-dark text-white font-medium rounded-lg transition-colors shadow-sm"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
