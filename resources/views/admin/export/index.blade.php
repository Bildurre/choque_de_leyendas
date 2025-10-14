<x-admin-layout>
  <x-admin.page-header :title="__('export.title')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.dashboard')"
        variant="secondary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_dashboard') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.export._database')
    
    <x-preview-management.section :title="__('export.database')">
      @include('admin.export._cards')
      @include('admin.export._heroes')
      @include('admin.export._counters')
      @include('admin.export._classes')
    </x-preview-management-section>
  </div>
</x-admin-layout>