<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('hero_races.edit') }}: {{ $heroRace->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.hero-races._form', [
      'heroRace' => $heroRace
    ])
  </div>
</x-admin-layout>