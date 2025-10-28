<x-admin-layout>
  <div class="dashboard">
    <div class="dashboard__header">
      <h1 class="dashboard__title">{{ __('admin.dashboard.title') }}</h1>
    </div>
    
    <div class="dashboard__content">
      <div class="dashboard__modules">
        @include('admin.dashboard.modules._faction-details-module')
        @include('admin.dashboard.modules._factions-module')
        @include('admin.dashboard.modules._cards-module')
        @include('admin.dashboard.modules._heroes-module')
        @include('admin.dashboard.modules._decks-module')
      </div>
    </div>
  </div>
</x-admin-layout>