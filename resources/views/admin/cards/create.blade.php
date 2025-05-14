<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.cards.create') }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.cards._form', [
      'factions' => $factions,
      'cardTypes' => $cardTypes,
      'equipmentTypes' => $equipmentTypes,
      'attackRanges' => $attackRanges,
      'attackSubtypes' => $attackSubtypes,
      'heroAbilities' => $heroAbilities
    ])
  </div>
</x-admin-layout>