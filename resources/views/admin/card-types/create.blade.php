<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.card_types.create') }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.card-types._form', [
      'availableSuperclasses' => $availableSuperclasses
    ])
  </div>
</x-admin-layout>