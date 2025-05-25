@php
  // Configuración del bloque
  $modelType = $block->settings['model_type'] ?? 'hero';
  $displayType = $block->settings['display_type'] ?? 'latest';
  $buttonText = $block->settings['button_text'] ?? __('pages.blocks.relateds.view_all');
  $textAlignment = $block->settings['text_alignment'] ?? 'left';
  
  // Determinar el modelo y la ruta
  $modelClass = match($modelType) {
    'faction' => \App\Models\Faction::class,
    'hero' => \App\Models\Hero::class,
    'card' => \App\Models\Card::class,
    default => \App\Models\Hero::class,
  };
  
  $indexRoute = match($modelType) {
    'faction' => route('public.factions.index'),
    'hero' => route('public.heroes.index'),
    'card' => route('public.cards.index'),
    default => route('public.heroes.index'),
  };
  
  // Obtener los elementos
  $query = $modelClass::published();
  
  if ($displayType === 'random') {
    $items = $query->inRandomOrder()->limit(4)->get();
  } else {
    $items = $query->latest()->limit(4)->get();
  }
@endphp

<section class="block block--relateds" 
  @if($block->background_color && $block->background_color != 'none') 
    data-background="{{ $block->background_color }}"
  @endif
>
  <div class="block__inner">
    <div class="relateds-block__header text-{{ $textAlignment }}">
      <div class="relateds-block__content">
        @if($block->title)
          <h2 class="block__title">{{ $block->title }}</h2>
        @endif
        
        @if($block->subtitle)
          <h3 class="block__subtitle">{{ $block->subtitle }}</h3>
        @endif
      </div>
      
      <div class="relateds-block__action">
        <x-button-link
          :href="$indexRoute"
          variant="secondary"
          size="md"
        >
          {{ $buttonText }}
        </x-button-link>
      </div>
    </div>
    
    <div class="relateds-block__grid">
      @foreach($items as $item)
        <div class="relateds-block__item">
          @php
            $showRoute = match($modelType) {
              'faction' => route('public.factions.show', $item),
              'hero' => route('public.heroes.show', $item),
              'card' => route('public.cards.show', $item),
              default => route('public.heroes.show', $item),
            };
          @endphp
          <a href="{{ $showRoute }}" class="relateds-block__link">
            <x-previews.preview-image :entity="$item" :type="$modelType" />
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>