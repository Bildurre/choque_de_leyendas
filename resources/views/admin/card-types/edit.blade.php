<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.card_types.edit') }}: {{ $cardType->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.card-types._form', [
      'cardType' => $cardType,
      'availableSuperclasses' => $availableSuperclasses
    ])
  </div>
</x-admin-layout>