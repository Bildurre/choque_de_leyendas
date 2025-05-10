<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('hero_abilities.edit') }}: {{ $heroAbility->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.hero-abilities._form', [
      'heroAbility' => $heroAbility,
      'attackRanges' => $attackRanges,
      'attackSubtypes' => $attackSubtypes
    ])
  </div>
</x-admin-layout>