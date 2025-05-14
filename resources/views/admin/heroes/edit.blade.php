<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.heroes.edit') }}: {{ $hero->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.heroes._form', [
      'hero' => $hero,
      'factions' => $factions,
      'heroRaces' => $heroRaces,
      'heroClasses' => $heroClasses,
      'heroAbilities' => $heroAbilities,
      'selectedAbilities' => $selectedAbilities,
      'attributesConfig' => $attributesConfig
    ])
  </div>
</x-admin-layout>