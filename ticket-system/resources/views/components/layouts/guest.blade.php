<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sistema de Gestion de Incidentes' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen antialiased">
    
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            
            <!-- Logo / Brand -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-slate-900">
                    Cloud Tecnologies
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    Sistema de Gestion de Incidentes
                </p>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-2xl shadow-xl p-8 space-y-6">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} Cloud Tecnologies. Todos los derechos reservados.
            </p>

        </div>
    </div>

    @livewireScripts
</body>
</html>
