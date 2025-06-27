@props([
  'entity',
  'type',
  'copies' => 1
])

<div class="collection-item" data-type="{{ $type }}" data-id="{{ $entity->id }}">
  <div class="collection-item__preview">
    @if($type === 'hero')
      <x-previews.hero :hero="$entity" />
    @else
      <x-previews.card :card="$entity" />
    @endif
  </div>
  <div class="collection-item__controls">
    <div class="collection-item__quantity-group">
      <label>{{ __('public.copies') }}:</label>
      <input 
        type="number" 
        class="collection-item__quantity" 
        value="{{ $copies }}" 
        min="1" 
        max="99"
      >
    </div>
    <button 
      type="button"
      class="collection-item__remove"
      data-type="{{ $type }}"
      data-id="{{ $entity->id }}"
      title="{{ __('public.remove_from_collection') }}"
    >
      <x-icon name="x" />
    </button>
  </div>
</div>