@props([
  'card',
  'isSelected' => false,
  'copies' => 1
])

<div class="card-item" data-card-item-inner>
  <div class="card-item__header">
    <span class="card-item__name">{{ $card->name }}</span>
    @if($card->faction)
      <span class="card-item__badge card-item__badge--faction">
        {{ $card->faction->name }}
      </span>
    @endif
  </div>

  <div class="card-item__meta">
    @if($card->cardType)
      <span class="card-item__meta-item">
        {{ $card->cardType->name }}
      </span>
    @endif
    
    @if($card->cost)
      <span class="card-item__meta-item card-item__meta-item--cost">
        <x-cost-display :cost="$card->cost" />
      </span>
    @endif

    @if($card->is_unique)
      <span class="card-item__meta-item">
        {{ __('entities.cards.is_unique') }}
      </span>
    @endif
  </div>

  @if($isSelected)
    {{-- Copies control --}}
    <div class="card-item__copies" data-copies-control>
      <button 
        type="button" 
        class="card-item__copies-btn" 
        data-decrease-copies
        aria-label="{{ __('entities.cards.decrease_copies') }}"
      >
        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M2 6H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
      
      <span class="card-item__copies-count" data-copies-count>{{ $copies }}</span>
      
      <button 
        type="button" 
        class="card-item__copies-btn" 
        data-increase-copies
        aria-label="{{ __('entities.cards.increase_copies') }}"
      >
        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 2V10M2 6H10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
    </div>

    {{-- Remove button --}}
    <button 
      type="button" 
      class="card-item__remove" 
      data-remove-card
      aria-label="{{ __('entities.cards.remove_card') }}"
    >
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
  @endif
</div>