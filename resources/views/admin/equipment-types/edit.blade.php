<x-admin-layout
  title='{{ __("equipment_types.edit") }}'
  headerTitle='{{ __("equipment_types.edit") }}'
  containerTitle='{{ __("equipment_types.title") }}'
  subtitle="{{ __('equipment_types.edit_subtitle_with_name', ['name' => $equipmentType->name]) }}"
  :createRoute="route('admin.equipment-types.create')"
  :backRoute="route('admin.equipment-types.index')"
>

  <x-forms.equipment-type-form 
    :equipmentType="$equipmentType"
    :submitLabel="__('common.actions.save_changes')" 
    :cancelRoute="route('admin.equipment-types.index')" 
  />

</x-admin-layout>