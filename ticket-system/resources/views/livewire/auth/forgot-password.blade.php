<div>
    <div class="text-center mb-6">
        <div class="mx-auto h-12 w-12 bg-amber-100 rounded-full flex items-center justify-center mb-4">
            <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-900">Recuperar Contraseña</h3>
        <p class="text-sm text-slate-600 mt-1">Ingresa tu correo para recibir un codigo de recuperacion</p>
    </div>

    @if($message)
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <svg class="h-5 w-5 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-green-800">Codigo enviado</p>
                    <p class="text-sm text-green-700 mt-1">{{ $message }}</p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="sendResetCode" class="space-y-5">
        
        <!-- Email -->
        <div>
            <x-label for="email" value="Correo electronico" />
            <x-input 
                id="email" 
                type="email" 
                wire:model="email"
                placeholder="tu@email.com"
                required 
                autofocus 
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-button type="submit" variant="primary">
                Enviar Codigo
            </x-button>
        </div>

        @if(session('codigo_enviado'))
        <div class="pt-4">
            <a href="{{ route('password.reset') }}" class="block text-center py-3 px-4 bg-slate-100 text-slate-700 font-semibold rounded-lg hover:bg-slate-200 transition">
                Tengo un codigo
            </a>
        </div>
        @endif

        <!-- Back to Login -->
        <div class="text-center pt-4 border-t border-slate-200">
            <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                Volver al inicio de sesion
            </a>
        </div>

    </form>
</div>
