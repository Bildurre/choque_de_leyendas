<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.hero_classes.create') }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.hero-classes._form', [
      'heroSuperclasses' => $heroSuperclasses
    ])
  </div>
</x-admin-layout>