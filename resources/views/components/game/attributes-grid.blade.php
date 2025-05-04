@props(['columns' => 3, 'showTotal' => false, 'totalValue' => null, 'showHealth' => false, 'healthValue' => null])

<div class="attributes-grid attributes-grid--{{ $columns }}-cols">
  {{ $slot }}
  
  @if($showTotal)
    <div class="attribute-item attribute-item--total">
      <span class="attribute-item__label">Total</span>
      <span class="attribute-item__value">{{ $totalValue }}</span>
    </div>
  @endif
  
  @if($showHealth)
    <div class="attribute-item attribute-item--health">
      <span class="attribute-item__label">Vida</span>
      <span class="attribute-item__value">{{ $healthValue }}</span>
    </div>
  @endif
</div>