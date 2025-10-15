<x-public-layout :title="__('errors.503_code')">
  <x-page-background :image="asset('storage/images/pages/503.jpeg')" />
  
  <div class="error-page">
    <div class="error-page__container">
      <h1 class="error-page__code">503</h1>
      
      <h2 class="error-page__title">
        {{ __('errors.503_title') }}
      </h2>
      
      <p class="error-page__message">
        {{ __('errors.503_message') }}
      </p>
      
      <div class="error-page__actions">
        <x-button 
          onclick="location.reload()" 
          variant="primary" 
          icon="refresh"
        >
          {{ __('errors.retry') }}
        </x-button>
      </div>
    </div>
  </div>
</x-public-layout>