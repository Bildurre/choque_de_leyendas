<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('cards.edit') }}: {{ $card->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.cards._form', [
      'card' => $card,
      'factions' => $factions,
      'cardTypes' => $cardTypes,
      'equipmentTypes' => $equipmentTypes,
      'attackRanges' => $attackRanges,
      'attackSubtypes' => $attackSubtypes,
      'heroAbilities' => $heroAbilities
    ])
  </div>
</x-admin-layout>