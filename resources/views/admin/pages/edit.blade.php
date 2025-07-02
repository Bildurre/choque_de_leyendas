<x-admin-layout>
  <x-admin.page-header :title="__('pages.edit') . ': ' . $page->title">
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
    <x-collapsible-section id="page-form-section" title="{{ __('pages.edit_details') }}">
      @include('admin.pages._form', [
        'page' => $page,
        'templates' => $templates,
        'pages' => $pages,
        'show_cancel_button' => false
      ])
    </x-collapsible-section>
    
    <!-- Blocks Section -->
    {{-- <x-collapsible-section id="page-blocks-section" title="{{ __('pages.blocks.page_blocks') }}"> --}}
      @include('admin.pages._blocks-manager', ['page' => $page])
    {{-- </x-collapsible-section> --}}
  </div>
</x-admin-layout>