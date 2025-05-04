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
      <x-core.button route="{{ $createRoute }}">
        @if ($createIcon)
          <x-icon name="{{ $createIcon }}" />
        @endif
        {{ $createLabel }}
      </x-core.button>
    @endif
    
    @if($backRoute)
      <x-core.button route="{{ $backRoute }}">
        @if ($backIcon)
          <x-icon name="{{ $backIcon }}" />
        @endif
        {{ $backLabel }}
      </x-core.button>
    @endif
  </div>
</div>