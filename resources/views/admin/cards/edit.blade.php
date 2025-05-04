<x-admin-layout
  title='{{ __("cards.edit") }}'
  headerTitle='{{ __("cards.edit") }}'
  containerTitle='{{ __("cards.title") }}'
  subtitle="{{ __('cards.edit_subtitle_with_name', ['name' => $card->name]) }}"
  :createRoute="route('admin.cards.create')"
  :backRoute="route('admin.cards.index')"
>

  <x-admin.forms.card-form 
    :card="$card"
    :factions="$factions"
    :cardTypes="$cardTypes"
    :equipmentTypes="$equipmentTypes"
    :attackRanges="$attackRanges"
    :attackSubtypes="$attackSubtypes"
    :heroAbilities="$heroAbilities"
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.cards.index')" 
  />

</x-admin-layout>