<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Dashboard Administrativo</h2>
            <p class="mt-2 text-sm text-gray-600">Vista general del sistema de tickets</p>
        </div>

        <!-- Estadísticas Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Total -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Tickets</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $stats['total'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendientes -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pendientes</dt>
                                <dd class="text-3xl font-semibold text-amber-600">{{ $stats['pendientes'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En Proceso -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">En Proceso</dt>
                                <dd class="text-3xl font-semibold text-blue-600">{{ $stats['en_proceso'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resueltos -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Resueltos</dt>
                                <dd class="text-3xl font-semibold text-green-600">{{ $stats['resueltos'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Tickets Urgentes Pendientes -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔥 Tickets Urgentes Pendientes</h3>
                    
                    @if($ticketsPendientesUrgentes->isEmpty())
                        <p class="text-gray-500 text-center py-8">No hay tickets urgentes pendientes</p>
                    @else
                        <div class="space-y-4">
                            @foreach($ticketsPendientesUrgentes as $ticket)
                                <div class="border-l-4 border-red-500 pl-4 py-2 hover:bg-gray-50 cursor-pointer">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $ticket->titulo }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $ticket->usuario->name }} • {{ $ticket->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Alta
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('admin.tickets.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Ver todos los tickets →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tickets Recientes -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Actividad Reciente</h3>
                    
                    @if($ticketsRecientes->isEmpty())
                        <p class="text-gray-500 text-center py-8">No hay tickets recientes</p>
                    @else
                        <div class="space-y-3">
                            @foreach($ticketsRecientes as $ticket)
                                <div class="flex items-center justify-between py-2 hover:bg-gray-50 cursor-pointer rounded px-2">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($ticket->estado->value === 0)
                                                <span class="h-2 w-2 rounded-full bg-amber-400 inline-block"></span>
                                            @elseif($ticket->estado->value === 1)
                                                <span class="h-2 w-2 rounded-full bg-blue-400 inline-block"></span>
                                            @else
                                                <span class="h-2 w-2 rounded-full bg-green-400 inline-block"></span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $ticket->titulo }}</p>
                                            <p class="text-xs text-gray-500">{{ $ticket->usuario->name }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
