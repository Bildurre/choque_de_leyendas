<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.heroes.create') }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.heroes._form', [
      'factions' => $factions,
      'heroRaces' => $heroRaces,
      'heroClasses' => $heroClasses,
      'heroAbilities' => $heroAbilities,
      'attributesConfig' => $attributesConfig
    ])
  </div>
</x-admin-layout>