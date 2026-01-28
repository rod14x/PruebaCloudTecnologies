<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - Sistema de Gestión de Incidentes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 antialiased">
    
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-8">
                    <a href="{{ auth()->user()->esAdministrador() ? route('dashboard') : route('tickets.index') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/cloudT_Logo.webp') }}" alt="Cloud Tecnologies" class="h-10 w-auto">
                        <span class="text-xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Cloud Tecnologies</span>
                    </a>

                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-4">
                        @if(auth()->user()->esAdministrador())
                            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }} transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('tickets.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tickets.index') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }} transition">
                                Mis Tickets
                            </a>
                            <a href="{{ route('tickets.create') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tickets.create') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }} transition">
                                Crear Ticket
                            </a>
                        @endif
                    </nav>
                </div>

                <div class="flex items-center gap-8">
                    <span class="px-4 py-2 text-xs font-medium rounded-full {{ auth()->user()->esAdministrador() ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-brand-primary' }}">
                        {{ auth()->user()->rol->nombre() }}: {{ auth()->user()->name }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-5 py-3 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-brand-primary-dark transition shadow-sm">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
