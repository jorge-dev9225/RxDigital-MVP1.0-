<x-guest-layout>
    <x-slot name="logo">
        <x-app-logo class="h-20 w-auto mx-auto" />
    </x-slot>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre y Apellido')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Género -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Género')" />
            <select id="gender" name="gender"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Selecciona una opción</option>
                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Masculino (Dr.)</option>
                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Femenino (Dra.)</option>
                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Otro / Prefiero no decir
                </option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Especialidad -->
        <div class="mt-4">
            <x-input-label for="specialty" :value="__('Especialidad')" />
            <x-text-input id="specialty" name="specialty" type="text" class="block mt-1 w-full" :value="old('specialty')"
                required />
            <x-input-error :messages="$errors->get('specialty')" class="mt-2" />
        </div>

        <!-- Matrícula nacional -->
        <div class="mt-4">
            <x-input-label for="license_number" :value="__('Matrícula nacional')" />
            <x-text-input id="license_number" name="license_number" type="text" class="block mt-1 w-full"
                :value="old('license_number')" required />
            <x-input-error :messages="$errors->get('license_number')" class="mt-2" />
        </div>

        <!-- Matrícula provincial (opcional) -->
        <div class="mt-4">
            <x-input-label for="provincial_license_number" :value="__('Matrícula provincial (opcional)')" />
            <x-text-input id="provincial_license_number" name="provincial_license_number" type="text"
                class="block mt-1 w-full" :value="old('provincial_license_number')" />
            <x-input-error :messages="$errors->get('provincial_license_number')" class="mt-2" />
        </div>

        <!-- Domicilio laboral (opcional) -->
        <div class="mt-4">
            <x-input-label for="clinic_address" :value="__('Domicilio laboral (opcional)')" />
            <x-text-input id="clinic_address" name="clinic_address" type="text" class="block mt-1 w-full"
                :value="old('clinic_address')" />
            <x-input-error :messages="$errors->get('clinic_address')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
