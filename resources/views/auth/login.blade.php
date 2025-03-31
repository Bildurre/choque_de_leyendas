<x-guest-layout>
  <div class="login-container">
    <div class="login-hexagon-logo">
      <svg viewBox="0 0 100 100">
        <!-- Hexagonal logo based on game design -->
      </svg>
    </div>

    <form method="POST" action="{{ route('login') }}" class="login-form">
      @csrf

      <!-- Email Input -->
      <div class="form-group">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input 
          id="email" 
          class="block mt-1 w-full" 
          type="email" 
          name="email" 
          :value="old('email')" 
          required 
          autofocus 
          autocomplete="username"
          x-data 
          x-bind:class="{'input-error': $error('email')}"
        />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>

      <!-- Password Input -->
      <div class="form-group">
        <x-input-label for="password" :value="__('Password')" />
        <x-text-input 
          id="password" 
          class="block mt-1 w-full"
          type="password"
          name="password"
          required 
          autocomplete="current-password"
          x-data
          x-bind:class="{'input-error': $error('password')}"
        />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
      </div>

      <!-- Remember Me -->
      <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center">
          <input 
            id="remember_me" 
            type="checkbox" 
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
            name="remember"
          >
          <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
      </div>

      <!-- Submit Button -->
      <div class="flex items-center justify-end mt-4">
        @if (Route::has('password.request'))
          <a 
            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
            href="{{ route('password.request') }}"
          >
            {{ __('Forgot your password?') }}
          </a>
        @endif

        <x-primary-button class="ml-3">
          {{ __('Log in') }}
        </x-primary-button>
      </div>
    </form>
  </div>
</x-guest-layout>