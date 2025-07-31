<x-admin-layout>
  <x-admin.page-header :title="__('entities.factions.edit')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.factions.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.factions._form', [
      'faction' => $faction
    ])
  </div>
</x-admin-layout>