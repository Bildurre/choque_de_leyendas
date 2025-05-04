<x-guest-layout>
  <auth.x-auth-session-status :status="session('status')" />

  <form 
    action="{{ route('login') }}"" 
    method="POST" 
    enctype="multipart/form-data" 
    class="login-form"
  >
    @csrf
    
    <x-form.card 
      submit_label="Login"
      cancel_route="{{ route('welcome') }}"
      cancel_label="⬅ Volver"
    >
      <h2>Login</h2>
      <x-form.field 
        name="email" 
        label="email" 
        :value="old('email')" 
        required
      />

      <x-form.field 
        name="password" 
        label="password"
        type="password"
        required
      />

      <div class="form-row">
        <x-form.checkbox 
          name="remember" 
          label="remember me"
        />
        
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="pswd-forgot">
            {{ __('Forgot your password?') }}
          </a>
        @endif
      </div>
    </x-form.card>
  </form>
</x-guest-layout>
