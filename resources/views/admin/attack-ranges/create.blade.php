<x-admin-layout>
  <x-admin.page-header :title="__('entities.attack_ranges.create')">
    <x-slot:actions>
        <x-button-link
          :href="route('admin.attack-ranges.index')"
          variant="primary"
          icon="arrow-left"
        >
          {{ __('admin.back_to_list') }}
        </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.attack-ranges._form')
  </div>
</x-admin-layout>