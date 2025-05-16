<x-admin-layout>
  <x-admin.page-header :title="__('pages.create')">
    <x-slot:actions>
      <x-button-link 
        :href="route('admin.pages.index')" 
        icon="arrow-left" 
        variant="secondary"
      >
        {{ __('pages.back_to_list') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    @include('admin.pages._form', [
      'templates' => $templates,
      'pages' => $pages
    ])
  </div>
</x-admin-layout>