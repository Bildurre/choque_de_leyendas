@props([
  'title',
  'subtitle' => '',
  'backIcon' => 'arrow-left',
  'backRoute' => null,
  'backLabel' => 'Volver',
  'createRoute' => null,
  'createIcon' => 'plus',
  'createLabel' => 'Nueva'
])

<div class="header-actions-bar">
  <div class="left-actions">
    <h1>{{ $title }}</h1>
    @if($subtitle)
      <p>{{ $subtitle }}</p>
    @endif
  </div>
  <div class="right-actions">
    @if($createRoute)
      <x-button route="{{ $createRoute }}">
        @if ($createIcon)
          <x-icon name="{{ $createIcon }}" />
        @endif
        {{ $createLabel }}
      </x-button>
    @endif
    
    @if($backRoute)
      <x-button route="{{ $backRoute }}">
        @if ($backIcon)
          <x-icon name="{{ $backIcon }}" />
        @endif
        {{ $backLabel }}
      </x-button>
    @endif
  </div>
</div>