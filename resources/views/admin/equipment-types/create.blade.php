<x-admin-layout>
  <x-admin.page-header :title="__('entities.equipment_types.create')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.equipment-types.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.equipment-types._form', [
      'categories' => $categories
    ])
  </div>
</x-admin-layout>