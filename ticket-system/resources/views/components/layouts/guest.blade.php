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
    
    <div class="min-h-screen flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Tarjeta Izquierda: Logo y Branding -->
                <div class="bg-white rounded-2xl shadow-lg border border-brand-neutral p-8 flex flex-col items-center justify-center">
                    <div class="text-center">
                        <div class="mx-auto w-48">
                            <img src="{{ asset('images/cloudT_Logo.webp') }}" alt="Cloud Tecnologies" class="w-full h-auto">
                        </div>
                        <h2 class="mt-6 text-2xl font-bold text-brand-secondary">
                            Sistema de Gestión de Incidentes
                        </h2>
                        <p class="mt-2 text-sm text-gray-600">
                            Soluciones Inteligentes 360
                        </p>
                        <div class="mt-6 pt-6 border-t border-brand-neutral">
                            <p class="text-xs text-gray-500">
                                Gestiona y resuelve incidencias de manera eficiente con nuestra plataforma integral
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Derecha: Formulario -->
                <div class="bg-white rounded-2xl shadow-lg border border-brand-neutral p-8 flex flex-col justify-center">
                    {{ $slot }}
                </div>

            </div>
            
            <!-- Footer -->
            <p class="text-center text-xs text-slate-500 mt-6">
                &copy; {{ date('Y') }} Cloud Tecnologies. Todos los derechos reservados.
            </p>
        </div>
    </div>

    @livewireScripts
</body>
</html>
