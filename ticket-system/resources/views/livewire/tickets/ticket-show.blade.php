<div wire:poll.5s="refresh">
    <x-slot name="title">Ticket #{{ $ticket->id }} - {{ $ticket->titulo }}</x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ auth()->user()->esAdministrador() ? route('admin.dashboard') : route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-brand-primary">
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
                            <a href="{{ auth()->user()->esAdministrador() ? route('admin.tickets.index') : route('tickets.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-brand-primary md:ml-2">
                                Tickets
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Ticket #{{ $ticket->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $ticket->titulo }}</h2>
                        <p class="text-sm text-gray-600">Ticket #{{ $ticket->id }} • Creado {{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @if($ticket->estado === App\Enums\EstadoTicket::PENDIENTE) bg-gray-100 text-gray-800
                            @elseif($ticket->estado === App\Enums\EstadoTicket::EN_PROCESO) bg-orange-100 text-brand-primary
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $ticket->estado->nombre() }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @if($ticket->prioridad->value === 2) bg-red-100 text-red-800
                            @elseif($ticket->prioridad->value === 1) bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ $ticket->prioridad->nombre() }}
                        </span>
                        @if(auth()->user()->esAdministrador())
                            <a 
                                href="{{ route('tickets.cambiar-estado', $ticket->id) }}"
                                class="inline-flex items-center px-3 py-1.5 bg-brand-primary hover:bg-brand-primary-dark text-white text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Cambiar Estado
                            </a>
                        @endif
                    </div>
                </div>
            </div>

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

            @if (session('message'))
                <div class="fixed top-20 right-4 z-50 max-w-md" 
                     x-data="{ show: true }" 
                     x-show="show"
                     x-init="setTimeout(() => { show = false; }, 5000)">
                    <x-toast type="{{ session('type', 'success') }}">
                        {{ session('message') }}
                    </x-toast>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna Principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Información del Ticket -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Descripción</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->descripcion }}</p>

                        <!-- Archivos Adjuntos -->
                        @if($ticket->archivosAdjuntos->count() > 0)
                            <div class="mt-6" x-data="{ modalOpen: false, currentImage: '' }">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    Archivos Adjuntos ({{ $ticket->archivosAdjuntos->count() }})
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                                    @foreach($ticket->archivosAdjuntos as $archivo)
                                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                            @php
                                                $extension = pathinfo($archivo->nombre_archivo, PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            @endphp
                                            
                                            @if($isImage)
                                                <!-- Preview de Imagen -->
                                                <div class="relative group cursor-pointer h-32" 
                                                     @click="modalOpen = true; currentImage = '{{ Storage::url($archivo->ruta_archivo) }}'">
                                                    <img src="{{ Storage::url($archivo->ruta_archivo) }}" 
                                                         alt="{{ $archivo->nombre_archivo }}"
                                                         class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-opacity flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Icono para archivos no imagen -->
                                                <div class="w-full h-32 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                                                    <div class="text-center">
                                                        <svg class="h-8 w-8 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                        <p class="text-xs text-gray-500 mt-2">.{{ $extension }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                            <!-- Info y Botón de Descarga -->
                                            <div class="p-2 bg-white border-t border-gray-200">
                                                <p class="text-xs font-medium text-gray-900 truncate mb-1" title="{{ $archivo->nombre_archivo }}">
                                                    {{ $archivo->nombre_archivo }}
                                                </p>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-500">
                                                        {{ number_format($archivo->tamano / 1024, 1) }} KB
                                                    </span>
                                                    <a href="{{ Storage::url($archivo->ruta_archivo) }}" 
                                                       download="{{ $archivo->nombre_archivo }}"
                                                       class="inline-flex items-center text-xs text-brand-primary hover:text-brand-primary-dark font-medium">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                        </svg>
                                                        Descargar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Modal Lightbox para ver imagen completa -->
                                <div x-show="modalOpen" 
                                     x-transition:enter="ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     @click="modalOpen = false"
                                     class="fixed inset-0 z-50 overflow-hidden bg-black bg-opacity-90 flex items-center justify-center p-4"
                                     style="display: none;">
                                    <div class="relative max-w-7xl max-h-full" @click.stop>
                                        <!-- Botón cerrar -->
                                        <button @click="modalOpen = false" 
                                                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <!-- Imagen completa -->
                                        <img :src="currentImage" 
                                             class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
                                             @click.stop>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Comentarios -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Comentarios</h3>
                        
                        <!-- Lista de Comentarios -->
                        <div class="space-y-4 mb-6">
                            @forelse($ticket->comentarios->sortBy('created_at') as $comentario)
                                <div class="flex space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-brand-primary flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($comentario->usuario->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="flex-1 bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="text-sm font-semibold text-gray-900">{{ $comentario->usuario->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $comentario->created_at->diffForHumans() }}</p>
                                        </div>
                                        <p class="text-sm text-gray-700">{{ $comentario->contenido }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-4">No hay comentarios aún</p>
                            @endforelse
                        </div>

                        <!-- Formulario Nuevo Comentario -->
                        <form wire:submit.prevent="agregarComentario" class="border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Agregar Comentario
                            </label>
                            <textarea 
                                wire:model="nuevoComentario"
                                rows="3"
                                maxlength="1000"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary"
                                placeholder="Escribe tu comentario aquí..."
                            ></textarea>
                            <div class="mt-2 flex items-center justify-between">
                                <p class="text-sm text-gray-500">{{ strlen($nuevoComentario) }}/1000 caracteres</p>
                                <button 
                                    type="submit"
                                    class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary-dark focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2"
                                >
                                    Enviar Comentario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Columna Lateral -->
                <div class="space-y-6">
                    <!-- Información General -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Usuario</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $ticket->usuario->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $ticket->usuario->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Categoría</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $ticket->categoria->nombre }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Creado</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Actualizado</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $ticket->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Historial de Cambios -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Historial de Cambios</h3>
                        
                        @if($ticket->historialEstados->count() > 0)
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @foreach($ticket->historialEstados->sortByDesc('created_at') as $index => $cambio)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-brand-primary flex items-center justify-center ring-8 ring-white">
                                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5">
                                                        <div>
                                                            <p class="text-sm text-gray-900">
                                                                <span class="font-medium">{{ $cambio->usuario->name }}</span>
                                                                cambió de
                                                                <span class="font-medium text-gray-600">{{ $cambio->estado_anterior->nombre() }}</span>
                                                                a
                                                                <span class="font-medium text-brand-primary">{{ $cambio->estado_nuevo->nombre() }}</span>
                                                            </p>
                                                            @if($cambio->comentario)
                                                                <p class="mt-1 text-sm text-gray-600 bg-gray-50 p-2 rounded">
                                                                    "{{ $cambio->comentario }}"
                                                                </p>
                                                            @endif
                                                            <p class="mt-1 text-xs text-gray-500">
                                                                {{ $cambio->created_at->format('d/m/Y H:i') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center py-4">No hay cambios registrados</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    window.Echo.channel('ticket.{{ $ticket->id }}')
        .listen('TicketUpdated', (e) => {
            console.log('Ticket actualizado en tiempo real:', e);
            
            // Actualizar el componente Livewire
            $wire.refresh();
            
            // Mostrar notificación solo para usuarios no admin
            @if(!auth()->user()->esAdministrador())
                $wire.set('notification', 'El ticket ha sido actualizado por un administrador.');
                $wire.set('notificationType', 'info');
            @endif
        });
</script>
@endscript
