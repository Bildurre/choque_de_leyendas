<x-admin-layout
  title='{{ __("equipment_types.title") }}'
  headerTitle='{{ __("equipment_types.management") }}'
  containerTitle='{{ __("equipment_types.title") }}'
  subtitle='{{ __("equipment_types.management_subtitle") }}'
  :createRoute="route('admin.equipment-types.create')"
>

  <x-entities-grid 
    empty_message="{{ __('equipment_types.no_items') }}"
    :createRoute="route('admin.equipment-types.create')"
    createLabel="{{ __('equipment_types.create_first') }}"
  >
    @foreach($equipmentTypes as $equipmentType)
      <x-cards.equipment-type-card 
        :equipmentType="$equipmentType"
        :editRoute="route('admin.equipment-types.edit', $equipmentType)"
        :deleteRoute="route('admin.equipment-types.destroy', $equipmentType)"
      />
    @endforeach
  </x-entities-grid>

</x-admin-layout>