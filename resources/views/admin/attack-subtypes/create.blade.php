<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.attack_subtypes.create') }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.attack-subtypes._form', [
      'types' => $types
    ])
  </div>
</x-admin-layout>