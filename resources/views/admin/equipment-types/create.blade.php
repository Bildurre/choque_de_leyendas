<x-admin-layout
  title='{{ __("equipment_types.new") }}'
  headerTitle='{{ __("equipment_types.create") }}'
  containerTitle='{{ __("equipment_types.title") }}'
  subtitle='{{ __("equipment_types.create_subtitle") }}'
  :backRoute="route('admin.equipment-types.index')"
>

  <x-admin.forms.equipment-type-form 
    :submitLabel="__('common.actions.create_entity', ['entity' => __('equipment_types.singular')])" 
    :cancelRoute="route('admin.equipment-types.index')" 
  />

</x-admin-layout>