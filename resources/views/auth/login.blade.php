<x-login-layout>
  <div class="login-page">
    <div class="login-page__container">
      <h1 class="login-page__title"> 
        <x-application-logo gradient="true" 
        gradient-start="#ff9800" 
        gradient-end="#9575cd"
        stroke="#000000" 
        stroke-width="2" />
      </h1>
      
      <!-- Session Status -->
      <x-auth-session-status :status="session('status')" />

      <form method="POST" action="{{ route('login') }}" class="login-page__form">
        @csrf

        <!-- Email Address -->
        <div>
          <x-input-label for="email" :value="__('Email')" />
          <x-text-input 
            id="email" 
            class="login-page__input" 
            type="email" 
            name="email" 
            :value="old('email')" 
            required 
            autofocus 
            autocomplete="username" 
            placeholder="Email"
          />
          <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div>
          <x-input-label for="password" :value="__('Password')" />
          <x-text-input 
            id="password" 
            class="login-page__input"
            type="password"
            name="password"
            required 
            autocomplete="current-password" 
            placeholder="Password"
          />
          <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="login-page__links">
          <label for="remember_me" >
            <input 
              id="remember_me" 
              type="checkbox" 
              name="remember"
            >
            <spam>{{ __('Remember me') }}</spam>
          </label>

          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
              {{ __('Forgot password?') }}
            </a>
          @endif
        </div>

        <button type="submit" class="login-page__button">
          {{ __('Log in') }}
        </button>
      </form>
    </div>
  </div>
</x-login-layout>