<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.deck_attributes.create') }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.deck-attributes-configurations._form', [
      'gameModes' => $gameModes
    ])
  </div>
</x-admin-layout>