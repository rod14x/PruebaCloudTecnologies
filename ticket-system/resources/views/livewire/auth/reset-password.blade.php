<div>
    <div class="text-center mb-6">
        <div class="mx-auto h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center mb-4">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-900">Nueva Contraseña</h3>
        <p class="text-sm text-slate-600 mt-1">Ingresa tu codigo y establece una nueva contraseña</p>
    </div>

    <form wire:submit.prevent="resetPassword" class="space-y-4">
        
        <!-- Email -->
        <div>
            <x-label for="email" value="Correo electronico" />
            <x-input 
                id="email" 
                type="email" 
                wire:model="email"
                placeholder="tu@email.com"
                required 
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Codigo -->
        <div>
            <x-label for="codigo" value="Codigo de recuperacion" />
            <x-input 
                id="codigo" 
                type="text" 
                wire:model="codigo"
                placeholder="123456"
                maxlength="6"
                required 
                autofocus
            />
            <x-input-error :messages="$errors->get('codigo')" />
            <p class="text-xs text-slate-500 mt-1">Ingresa el codigo de 6 digitos que recibiste</p>
        </div>

        <!-- New Password -->
        <div>
            <x-label for="password" value="Nueva contraseña" />
            <x-input 
                id="password" 
                type="password" 
                wire:model="password"
                placeholder="Min. 8 caracteres"
                required 
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Password Confirmation -->
        <div>
            <x-label for="password_confirmation" value="Confirmar contraseña" />
            <x-input 
                id="password_confirmation" 
                type="password" 
                wire:model="password_confirmation"
                placeholder="Repite tu contraseña"
                required 
            />
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-button type="submit" variant="primary">
                Restablecer Contraseña
            </x-button>
        </div>

        <!-- Back Links -->
        <div class="flex justify-between items-center pt-4 border-t border-slate-200">
            <a href="{{ route('password.request') }}" class="text-sm font-medium text-slate-600 hover:text-slate-700 transition">
                Reenviar codigo
            </a>
            <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                Volver al inicio
            </a>
        </div>

    </form>
</div>
