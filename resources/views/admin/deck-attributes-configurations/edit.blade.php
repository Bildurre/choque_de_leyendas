<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.deck_attributes.edit') }}: {{ $configuration->gameMode->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.deck-attributes-configurations._form', [
      'configuration' => $configuration,
      'gameModes' => $gameModes
    ])
  </div>
</x-admin-layout>