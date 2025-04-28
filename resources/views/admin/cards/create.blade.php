<x-admin-layout
  title='{{ __("cards.new") }}'
  headerTitle='{{ __("cards.create") }}'
  containerTitle='{{ __("cards.title") }}'
  subtitle='{{ __("cards.create_subtitle") }}'
  :backRoute="route('admin.cards.index')"
>

  <x-forms.card-form 
    :factions="$factions"
    :cardTypes="$cardTypes"
    :equipmentTypes="$equipmentTypes"
    :attackRanges="$attackRanges"
    :attackSubtypes="$attackSubtypes"
    :heroAbilities="$heroAbilities"
    :submitLabel="__('common.actions.create_entity', ['entity' => __('cards.singular')])" 
    :cancelRoute="route('admin.cards.index')" 
  />

</x-admin-layout>