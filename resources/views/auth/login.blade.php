<x-public-layout>
  <x-auth.session-status :status="session('status')" />

  <form 
    action="{{ route('login') }}" 
    method="POST" 
    class="login-form"
  >
    @csrf
    
    <x-form.card 
      submit_label="{{ __('auth.login') }}"
      cancel_route="{{ route('welcome') }}"
      cancel_label="{{ __('auth.back') }}"
    >
      <x-slot:header>
        <h2>{{ __('auth.login_title') }}</h2>
      </x-slot:header>
      
      <x-form.input 
        type="email" 
        name="email" 
        label="{{ __('auth.email') }}" 
        :value="old('email')" 
        required 
        autofocus
        autocomplete="username"
      />

      <x-form.input 
        type="password" 
        name="password" 
        label="{{ __('auth.password') }}"
        required
        autocomplete="current-password"
      />

      <x-form.checkbox 
        name="remember" 
        label="{{ __('auth.remember_me') }}"
      />
      
      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="pswd-forgot">
          {{ __('auth.forgot_password') }}
        </a>
      @endif
    </x-form.card>
  </form>
</x-public-layout>