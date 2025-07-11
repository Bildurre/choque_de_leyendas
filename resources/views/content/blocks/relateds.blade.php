@php
  $relatedsContent = $block->getTranslation('content', app()->getLocale());
  $modelType = $block->settings['model_type'] ?? 'hero';
  $displayType = $block->settings['display_type'] ?? 'latest';
  $buttonText = $relatedsContent['button_text'] ?? '';
  
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
  
  $query = $modelClass::published();
  
  if ($displayType === 'random') {
    $items = $query->inRandomOrder()->limit(4)->get();
  } else {
    $items = $query->latest()->limit(4)->get();
  }
@endphp

<x-blocks.block :block="$block">
  <div class="block__content">
    <div class="relateds-block__header">
      <div class="relateds-block__title-wrapper">
        <x-blocks.titles :block="$block" />
      </div>
      
      @if ($buttonText)
        <div class="relateds-block__action">
           <x-button-link
              :href="$indexRoute"
              :variant="$block->settings['button_variant'] ?? 'primary'"
              :size="$block->settings['button_size'] ?? 'lg'"
            >
              {{ $buttonText }}
            </x-button-link>
        </div>
      @endif
      
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
</x-blocks.block>