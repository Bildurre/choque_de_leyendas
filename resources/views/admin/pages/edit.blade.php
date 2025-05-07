<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('pages.edit') }}: {{ $page->title }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.pages._form', [
      'page' => $page,
      'templates' => $templates,
      'pages' => $pages
    ])
  </div>
</x-admin-layout>