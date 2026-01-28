<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-brand-secondary">Mis Tickets</h1>
            <p class="text-gray-600 mt-1">Visualiza el estado de tus incidencias reportadas</p>
        </div>
        <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-brand-primary-dark transition">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Ticket
        </a>
    </div>

    <!-- Info: Auto-ocultación de tickets resueltos -->
    <div class="mb-6 bg-orange-50 border border-brand-primary rounded-lg p-4">
        <div class="flex items-start">
            <svg class="h-5 w-5 text-brand-primary mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-brand-secondary mb-1">Auto-archivo de tickets</h3>
                <p class="text-sm text-gray-700">
                    Los tickets resueltos se ocultarán automáticamente después de <strong>1 semana</strong> para mantener tu lista organizada. 
                    No se eliminan, solo dejan de mostrarse.
                </p>
            </div>
        </div>
    </div>

    @if($tickets->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
            <svg class="h-16 w-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">No tienes tickets</h3>
            <p class="text-slate-600 mb-6">Aún no has reportado ninguna incidencia</p>
            <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-brand-primary-dark transition">
                Crear mi primer ticket
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($tickets as $ticket)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Título y ID -->
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-xs font-mono text-slate-500">#{{ $ticket->id }}</span>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $ticket->titulo }}</h3>
                            </div>

                            <!-- Descripción -->
                            <p class="text-slate-600 text-sm mb-4 line-clamp-2">{{ $ticket->descripcion }}</p>

                            <!-- Metadata -->
                            <div class="flex flex-wrap items-center gap-4 text-sm">
                                <!-- Categoría -->
                                <div class="flex items-center text-slate-600">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ $ticket->categoria->nombre }}
                                </div>

                                <!-- Fecha -->
                                <div class="flex items-center text-slate-600">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $ticket->created_at->format('d/m/Y H:i') }}
                                </div>

                                <!-- Evidencia -->
                                @if($ticket->archivosAdjuntos->count() > 0)
                                    <div class="flex items-center text-slate-600">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Con evidencia
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Badges de estado y prioridad -->
                        <div class="flex flex-col items-end gap-2 ml-6">
                            <!-- Estado -->
                            <span class="px-3 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                {{ $ticket->estado->value === 0 ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $ticket->estado->value === 1 ? 'bg-orange-100 text-brand-primary' : '' }}
                                {{ $ticket->estado->value === 2 ? 'bg-green-100 text-green-700' : '' }}
                            ">
                                {{ $ticket->estado->nombre() }}
                            </span>

                            <!-- Aviso de próxima ocultación (si está resuelto hace más de 6 días) -->
                            @if($ticket->estado->value === 2 && $ticket->updated_at->diffInDays(now()) >= 6)
                                <span class="px-2 py-1 text-xs bg-orange-50 text-orange-700 rounded border border-orange-200">
                                    Se ocultará pronto
                                </span>
                            @endif

                            <!-- Prioridad -->
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full
                                    {{ $ticket->prioridad->value === 0 ? 'bg-brand-secondary' : '' }}
                                    {{ $ticket->prioridad->value === 1 ? 'bg-amber-500' : '' }}
                                    {{ $ticket->prioridad->value === 2 ? 'bg-red-500' : '' }}
                                "></span>
                                <span class="text-xs font-medium text-slate-600">
                                    Prioridad {{ $ticket->prioridad->nombre() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botón Ver Detalles -->
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('tickets.show', $ticket->id) }}">
                            <x-button type="button" variant="primary" class="w-full sm:w-auto">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver Detalles
                            </x-button>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Stats -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-800">Pendientes</p>
                        <p class="text-2xl font-bold text-amber-900 mt-1">
                            {{ $tickets->where('estado.value', 0)->count() }}
                        </p>
                    </div>
                    <div class="h-10 w-10 bg-amber-200 rounded-lg flex items-center justify-center">
                        <svg class="h-5 w-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-orange-50 rounded-lg p-4 border border-brand-primary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-brand-primary">En Proceso</p>
                        <p class="text-2xl font-bold text-brand-secondary mt-1">
                            {{ $tickets->where('estado.value', 1)->count() }}
                        </p>
                    </div>
                    <div class="h-10 w-10 bg-orange-200 rounded-lg flex items-center justify-center">
                        <svg class="h-5 w-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800">Resueltos</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">
                            {{ $tickets->where('estado.value', 2)->count() }}
                        </p>
                    </div>
                    <div class="h-10 w-10 bg-green-200 rounded-lg flex items-center justify-center">
                        <svg class="h-5 w-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
