<div>
    <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-slate-900">Crear Cuenta</h3>
        <p class="text-sm text-slate-600 mt-1">Completa tus datos para registrarte</p>
    </div>

    <form wire:submit.prevent="register" class="space-y-4">
        
        <!-- Name -->
        <div>
            <x-label for="name" value="Nombre completo" />
            <x-input 
                id="name" 
                type="text" 
                wire:model="name"
                placeholder="Juan Perez"
                required 
            />
            <x-input-error :messages="$errors->get('name')" />
        </div>

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
            <x-input-error for="email" />
        </div>

        <!-- DNI -->
        <div>
            <x-label for="dni" value="DNI" />
            <x-input 
                id="dni" 
                type="text" 
                wire:model="dni"
                placeholder="12345678"
                maxlength="20"
                required 
            />
            <x-input-error :messages="$errors->get('dni')" />
        </div>

        <!-- Telefono -->
        <div>
            <x-label for="telefono" value="Telefono" />
            <x-input 
                id="telefono" 
                type="text" 
                wire:model="telefono"
                placeholder="987654321"
                maxlength="20"
                required 
            />
            <x-input-error :messages="$errors->get('telefono')" />
        </div>

        <!-- Password -->
        <div>
            <x-label for="password" value="Contraseña" />
            <x-input 
                id="password" 
                type="password" 
                wire:model="password"
                placeholder="Min. 8 caracteres"
                required 
            />
            <x-input-error for="password" />
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
                Registrarme
            </x-button>
        </div>

        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-slate-200">
            <p class="text-sm text-slate-600">
                ¿Ya tienes cuenta? 
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700 transition">
                    Inicia sesion
                </a>
            </p>
        </div>

    </form>
</div>
