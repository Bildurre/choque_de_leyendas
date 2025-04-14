<x-guest-layout>
  <x-auth-session-status :status="session('status')" />

  <form 
    action="{{ route('login') }}"" 
    method="POST" 
    enctype="multipart/form-data" 
    class="login-form"
  >
    @csrf
    
    <x-forms.card 
      submit_label="Login"
      cancel_route="{{ route('wellcome') }}"
      cancel_label="Volver"
    >
      <h2>Login</h2>
      <x-forms.field 
        name="email" 
        label="email" 
        :value="old('email')" 
        :required="true" 
      />

      <x-forms.field 
        name="password" 
        label="password"
        type="password"
        :required="true" 
      />

      <div class="form-row">
        <x-forms.field 
          name="remember" 
          label="remember me"
          type="checkbox"
        />
        
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="pswd-forgot">
            {{ __('Forgot your password?') }}
          </a>
        @endif
      </div>
    </x-forms.card>
  </form>
</x-guest-layout>
