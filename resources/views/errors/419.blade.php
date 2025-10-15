<x-public-layout :title="__('errors.419_code')">
  <x-page-background :image="asset('storage/images/pages/419.jpeg')" />
  
  <div class="error-page">
    <div class="error-page__container">
      <h1 class="error-page__code">419</h1>
      
      <h2 class="error-page__title">
        {{ __('errors.419_title') }}
      </h2>
      
      <p class="error-page__message">
        {{ __('errors.419_message') }}
      </p>
      
      <div class="error-page__actions">
        <x-button 
          onclick="history.back()" 
          variant="primary" 
          icon="arrow-left"
        >
          {{ __('errors.go_back') }}
        </x-button>
        
        <x-button 
          onclick="location.reload()" 
          variant="secondary" 
          icon="refresh"
        >
          {{ __('errors.reload_page') }}
        </x-button>
      </div>
    </div>
  </div>
</x-public-layout>