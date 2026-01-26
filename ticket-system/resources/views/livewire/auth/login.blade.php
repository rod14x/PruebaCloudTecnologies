<div>
    <div class="text-center mb-6">
        <h3 class="text-2xl font-bold text-slate-900">Iniciar Sesion</h3>
        <p class="text-sm text-slate-600 mt-1">Ingresa tus credenciales para continuar</p>
    </div>

    <form wire:submit.prevent="login" class="space-y-5">
        
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
            <x-input-error for="email" />
        </div>

        <!-- Password -->
        <div>
            <x-label for="password" value="Contraseña" />
            <x-input 
                id="password" 
                type="password" 
                wire:model="password"
                placeholder="••••••••"
                required 
            />
            <x-input-error for="password" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    wire:model="remember"
                    class="h-4 w-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500"
                >
                <span class="ml-2 text-sm text-slate-700">Recordarme</span>
            </label>

            <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                Olvide mi contraseña
            </a>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-button type="submit" variant="primary">
                Iniciar Sesion
            </x-button>
        </div>

        <!-- Register Link -->
        <div class="text-center pt-4 border-t border-slate-200">
            <p class="text-sm text-slate-600">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-700 transition">
                    Registrate aqui
                </a>
            </p>
        </div>

    </form>
</div>
