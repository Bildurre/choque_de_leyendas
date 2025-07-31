<x-admin-layout>
  <x-admin.page-header :title="__('entities.cards.create')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.cards.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
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