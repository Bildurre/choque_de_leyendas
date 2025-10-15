<x-public-layout :title="__('errors.429_code')">
  <x-page-background :image="asset('storage/images/pages/429.jpeg')" />
  
  <div class="error-page">
    <div class="error-page__container">
      <h1 class="error-page__code">429</h1>
      
      <h2 class="error-page__title">
        {{ __('errors.429_title') }}
      </h2>
      
      <p class="error-page__message">
        {{ __('errors.429_message') }}
      </p>
      
      <div class="error-page__actions">
        <x-button-link 
          :href="route('welcome')" 
          variant="primary" 
          icon="home"
        >
          {{ __('errors.return_home') }}
        </x-button-link>
      </div>
    </div>
  </div>
</x-public-layout>