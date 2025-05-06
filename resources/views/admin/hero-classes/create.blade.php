<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('hero_classes.create') }}</h1>
  </div>
  
  <div class="page-content">
    <div class="card">
      <div class="card-body">
        @include('admin.hero-classes._form', [
          'heroSuperclasses' => $heroSuperclasses
        ])
      </div>
    </div>
  </div>
</x-admin-layout>