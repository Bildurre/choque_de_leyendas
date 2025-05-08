<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('pages.edit') }}: {{ $page->title }}</h1>
  </div>
  
  <div class="page-content">
    <x-accordion id="page-edit-accordion">
      <x-collapsible-section id="page-form-section" title="{{ __('pages.edit_details') }}">
        @include('admin.pages._form', [
          'page' => $page,
          'templates' => $templates,
          'pages' => $pages
        ])
      </x-collapsible-section>
      
      <!-- Blocks Section -->
      <x-collapsible-section id="page-blocks-section" title="{{ __('blocks.page_blocks') }}">
        @include('admin.pages._blocks-manager', ['page' => $page])
      </x-collapsible-section>
    </x-accordion>
  </div>
</x-admin-layout>