<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Dashboard Administrativo</h2>
            <p class="mt-2 text-sm text-gray-600">Vista general del sistema de tickets</p>
        </div>

        <!-- KPIs Principales -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
            <!-- Total Tickets -->
            <div class="bg-gradient-to-br from-brand-primary to-brand-primary-dark overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-orange-100 truncate">Total Tickets Registrados</dt>
                                <dd class="text-4xl font-bold text-white">{{ $totalTickets }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets Abiertos -->
            <div class="bg-gradient-to-br from-amber-500 to-amber-600 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-amber-100 truncate">Tickets Abiertos</dt>
                                <dd class="text-4xl font-bold text-white">{{ $ticketsAbiertos }}</dd>
                                <dd class="text-xs text-amber-100 mt-1">Pendientes + En Proceso</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets Cerrados -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-12 w-12 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-green-100 truncate">Tickets Cerrados</dt>
                                <dd class="text-4xl font-bold text-white">{{ $ticketsCerrados }}</dd>
                                <dd class="text-xs text-green-100 mt-1">Resueltos</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas por Estado -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
            <!-- Pendientes -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-gray-400">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pendientes</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $stats['pendientes'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En Proceso -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-brand-primary">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">En Proceso</dt>
                                <dd class="text-3xl font-semibold text-brand-primary">{{ $stats['en_proceso'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resueltos -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-green-400">
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

        <!-- Gráficos de Análisis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Gráfico de Abiertos vs Cerrados -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">📊 Tickets Abiertos vs Cerrados</h3>
                    <canvas id="estadoBarChart" class="max-h-64"></canvas>
                    
                    <!-- Estadísticas numéricas -->
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="text-center p-4 bg-amber-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Abiertos</p>
                            <p class="text-3xl font-bold text-amber-600">{{ $ticketsAbiertos }}</p>
                            <p class="text-xs text-gray-500 mt-1">Pendientes + En Proceso</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Cerrados</p>
                            <p class="text-3xl font-bold text-green-600">{{ $ticketsCerrados }}</p>
                            <p class="text-xs text-gray-500 mt-1">Resueltos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Prioridades -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">🎯 Distribución por Prioridad</h3>
                    <canvas id="prioridadPieChart" class="max-h-64"></canvas>
                    
                    <!-- Estadísticas numéricas -->
                    <div class="grid grid-cols-3 gap-4 mt-6">
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Baja</p>
                            <p class="text-3xl font-bold text-green-600">{{ $ticketsPorPrioridad['baja'] }}</p>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Media</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $ticketsPorPrioridad['media'] }}</p>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Alta</p>
                            <p class="text-3xl font-bold text-red-600">{{ $ticketsPorPrioridad['alta'] }}</p>
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
                        <a href="{{ route('admin.tickets.index') }}" class="text-sm text-brand-primary hover:text-brand-primary-dark font-medium">
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
                                                <span class="h-2 w-2 rounded-full bg-brand-primary inline-block"></span>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Barras: Abiertos vs Cerrados
    const barCtx = document.getElementById('estadoBarChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Abiertos', 'Cerrados'],
                datasets: [{
                    label: 'Cantidad de Tickets',
                    data: [
                        {{ $ticketsAbiertos }},
                        {{ $ticketsCerrados }}
                    ],
                    backgroundColor: [
                        'rgba(245, 158, 11, 0.8)',   // amber-500
                        'rgba(34, 197, 94, 0.8)'     // green-500
                    ],
                    borderColor: [
                        'rgba(245, 158, 11, 1)',
                        'rgba(34, 197, 94, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = {{ $totalTickets }};
                                const value = context.parsed.y;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + value + ' tickets (' + percentage + '%)';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Cantidad de Tickets'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Estado'
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Pastel: Distribución por Prioridad
    const pieCtx = document.getElementById('prioridadPieChart');
    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Baja', 'Media', 'Alta'],
                datasets: [{
                    data: [
                        {{ $ticketsPorPrioridad['baja'] }},
                        {{ $ticketsPorPrioridad['media'] }},
                        {{ $ticketsPorPrioridad['alta'] }}
                    ],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',   // green-500
                        'rgba(234, 179, 8, 0.8)',   // yellow-500
                        'rgba(239, 68, 68, 0.8)'    // red-500
                    ],
                    borderColor: [
                        'rgba(34, 197, 94, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' tickets (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
