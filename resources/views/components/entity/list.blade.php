@props([
  'title',
  'createRoute' => null,
  'createLabel' => __('admin.create'),
  'emptyMessage' => __('admin.no_records'),
  'items' => null
])

<div {{ $attributes->merge(['class' => 'entity-list']) }}>
  <div class="entity-list__header">
    <h1 class="entity-list__title">{{ $title }}</h1>
    
    <div class="entity-list__actions">
      @if($createRoute)
        <a href="{{ $createRoute }}" class="btn btn--primary">
          {{ $createLabel }}
        </a>
      @endif
      
      {{ $actions ?? '' }}
    </div>
  </div>
  
  @if(isset($filters))
    <div class="entity-list__filters">
      {{ $filters }}
    </div>
  @endif
  
  <div class="entity-list__content">
    @if(isset($items) && (is_countable($items) ? count($items) : 0) > 0)
      <div class="entity-list__items">
        {{ $slot }}
      </div>
      
      @if(isset($pagination))
        <div class="entity-list__pagination">
          {{ $pagination }}
        </div>
      @endif
    @else
      <div class="entity-list__empty">
        {{ $emptyMessage }}
      </div>
    @endif
  </div>
</div>