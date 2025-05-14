<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.attack_subtypes.edit') }}: {{ $attackSubtype->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.attack-subtypes._form', [
      'attackSubtype' => $attackSubtype,
      'types' => $types
    ])
  </div>
</x-admin-layout>