<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('pages.edit') }}: {{ $page->title }}</h1>
  </div>
  
  <div class="page-content">
    <x-collapsible-section id="page-form-section" title="{{ __('pages.edit_details') }}">
      @include('admin.pages._form', [
        'page' => $page,
        'templates' => $templates,
        'pages' => $pages
      ])
    </x-collapsible-section>
    
    <!-- Blocks Section -->
    @include('admin.pages._blocks-manager', ['page' => $page])
  </div>
</x-admin-layout>