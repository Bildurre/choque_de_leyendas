<x-admin-layout
  title='{{ __("card_types.edit") }}'
  headerTitle='{{ __("card_types.edit") }}'
  containerTitle='{{ __("card_types.title") }}'
  subtitle="{{ __('card_types.edit_subtitle_with_name', ['name' => $cardType->name]) }}"
  :createRoute="route('admin.card-types.create')"
  :backRoute="route('admin.card-types.index')"
>

  <x-forms.card-type-form 
    :cardType="$cardType"
    :availableSuperclasses="$availableSuperclasses"
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.card-types.index')" 
  />

</x-admin-layout>