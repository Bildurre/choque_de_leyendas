<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('entities.hero_classes.edit') }}: {{ $heroClass->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.hero-classes._form', [
      'heroClass' => $heroClass,
      'heroSuperclasses' => $heroSuperclasses
    ])
  </div>
</x-admin-layout>