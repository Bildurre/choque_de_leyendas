<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('factions.edit') }}: {{ $faction->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.factions._form', [
      'faction' => $faction
    ])
  </div>
</x-admin-layout>