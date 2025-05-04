<x-admin-layout
  title='{{ __("card_types.new") }}'
  headerTitle='{{ __("card_types.create") }}'
  containerTitle='{{ __("card_types.title") }}'
  subtitle='{{ __("card_types.create_subtitle") }}'
  :backRoute="route('admin.card-types.index')"
>

  <x-admin.forms.card-type-form 
    :availableSuperclasses="$availableSuperclasses"
    :submitLabel="__('common.actions.create_entity', ['entity' => __('card_types.singular')])" 
    :cancelRoute="route('admin.card-types.index')" 
  />

</x-admin-layout>