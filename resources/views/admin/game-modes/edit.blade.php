<x-admin-layout>
  <div class="page-header">
    <h1 class="page-title">{{ __('game_modes.edit') }}: {{ $gameMode->name }}</h1>
  </div>
  
  <div class="page-content">
    @include('admin.game-modes._form', [
      'gameMode' => $gameMode
    ])
  </div>
</x-admin-layout>