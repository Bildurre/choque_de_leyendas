<x-admin-layout>
  <x-admin.page-header :title="__('entities.counters.edit')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.counters.index')"
        variant="primary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.counters._form', [
      'counter' => $counter,
      'types' => $types
    ])
  </div>
</x-admin-layout>