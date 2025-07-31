<x-admin-layout>
  <x-admin.page-header :title="__('entities.attack_subtypes.edit')">
    <x-slot:actions>
        <x-button-link
          :href="route('admin.attack-subtypes.index')"
          variant="primary"
          icon="arrow-left"
        >
          {{ __('admin.back_to_list') }}
        </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.attack-subtypes._form', [
      'attackSubtype' => $attackSubtype,
      'types' => $types
    ])
  </div>
</x-admin-layout>