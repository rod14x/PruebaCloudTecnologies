<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-brand-secondary">Crear Nuevo Ticket</h1>
            <p class="text-gray-600 mt-1">Reporta una incidencia y adjunta evidencia si es necesario</p>
        </div>
        <x-back-button :href="route('tickets.index')" />
    </div>

    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-green-800 text-sm">{{ session('message') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <form wire:submit.prevent="createTicket" class="p-6 space-y-6">
            
            <!-- Título -->
            <div>
                <x-label for="titulo" value="Título del ticket" />
                <x-input 
                    id="titulo" 
                    type="text" 
                    wire:model="titulo" 
                    class="mt-1 block w-full" 
                    placeholder="Ej: Error en el sistema de reportes"
                />
                <x-input-error for="titulo" class="mt-2" />
            </div>

            <!-- Categoría y Prioridad en Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Categoría -->
                <div>
                    <x-label for="categoria_id" value="Categoría" />
                    <select 
                        id="categoria_id" 
                        wire:model="categoria_id" 
                        class="mt-1 block w-full border-brand-neutral focus:border-brand-primary focus:ring-brand-primary rounded-md shadow-sm"
                    >
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="categoria_id" class="mt-2" />
                </div>

                <!-- Prioridad -->
                <div>
                    <x-label for="prioridad" value="Prioridad" />
                    <div class="mt-3 space-y-3">
                        <label class="flex items-center">
                            <input 
                                type="radio" 
                                wire:model="prioridad" 
                                value="0" 
                                class="text-brand-secondary focus:ring-brand-secondary"
                            >
                            <span class="ml-2 flex items-center">
                                <span class="w-3 h-3 rounded-full bg-brand-secondary mr-2"></span>
                                <span class="text-sm text-slate-700">Baja</span>
                            </span>
                        </label>
                        
                        <label class="flex items-center">
                            <input 
                                type="radio" 
                                wire:model="prioridad" 
                                value="1" 
                                class="text-amber-600 focus:ring-amber-500"
                            >
                            <span class="ml-2 flex items-center">
                                <span class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span>
                                <span class="text-sm text-slate-700">Media</span>
                            </span>
                        </label>
                        
                        <label class="flex items-center">
                            <input 
                                type="radio" 
                                wire:model="prioridad" 
                                value="2" 
                                class="text-red-600 focus:ring-red-500"
                            >
                            <span class="ml-2 flex items-center">
                                <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                <span class="text-sm text-slate-700">Alta</span>
                            </span>
                        </label>
                    </div>
                    <x-input-error for="prioridad" class="mt-2" />
                </div>
            </div>

            <!-- Descripción -->
            <div>
                <x-label for="descripcion" value="Descripción detallada" />
                <textarea 
                    id="descripcion" 
                    wire:model="descripcion" 
                    rows="5" 
                    class="mt-1 block w-full border-brand-neutral focus:border-brand-primary focus:ring-brand-primary rounded-md shadow-sm"
                    placeholder="Describe el problema con el mayor detalle posible..."
                ></textarea>
                <x-input-error for="descripcion" class="mt-2" />
            </div>

            <!-- Evidencia -->
            <div>
                <x-label for="evidencia" value="Evidencia (opcional)" />
                <div class="mt-3 bg-gray-50 border-2 border-dashed border-brand-neutral rounded-lg p-6 hover:border-brand-primary hover:bg-orange-50 transition">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <div class="mt-4">
                            <label for="evidencia" class="cursor-pointer">
                                <span class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                                    <svg class="h-5 w-5 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Seleccionar archivo
                                </span>
                                <input 
                                    type="file" 
                                    id="evidencia" 
                                    wire:model="evidencia" 
                                    accept="image/*"
                                    class="hidden"
                                >
                            </label>
                            <p class="mt-2 text-xs text-slate-500">PNG, JPG o GIF. Máximo 2MB.</p>
                        </div>
                    </div>
                </div>
                <x-input-error for="evidencia" class="mt-2" />
                
                @if ($evidencia)
                    <div class="mt-4 bg-white border border-slate-200 rounded-lg p-4">
                        <div class="flex items-start gap-4">
                            <img src="{{ $evidencia->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg border border-slate-200">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900">Vista previa</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $evidencia->getClientOriginalName() }}</p>
                                <p class="text-xs text-slate-500">{{ number_format($evidencia->getSize() / 1024, 2) }} KB</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div wire:loading wire:target="evidencia" class="mt-3 bg-orange-50 border border-brand-primary rounded-lg p-3">
                    <div class="flex items-center">
                        <svg class="animate-spin h-5 w-5 text-brand-primary mr-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm text-orange-700 font-medium">Cargando imagen...</span>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-between gap-6 pt-6 mt-6 border-t border-slate-200">
                <x-back-button :href="route('tickets.index')" />
                <x-button type="submit" wire:loading.attr="disabled" class="shadow-lg">
                    <span wire:loading.remove wire:target="createTicket">Crear Ticket</span>
                    <span wire:loading wire:target="createTicket">Creando...</span>
                </x-button>
            </div>

        </form>
    </div>

    <!-- Información adicional -->
    <div class="mt-6 bg-orange-50 border border-brand-primary rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-brand-secondary">Información importante</h3>
                <div class="mt-2 text-sm text-gray-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Los tickets se asignan automáticamente con estado "Pendiente"</li>
                        <li>Recibirás notificaciones sobre el progreso de tu ticket</li>
                        <li>Adjunta evidencia fotográfica para una resolución más rápida</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
