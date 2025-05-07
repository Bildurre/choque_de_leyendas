<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('pages.create') }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.pages._form', [
      'templates' => $templates,
      'pages' => $pages
    ])
  </div>
</x-admin-layout>