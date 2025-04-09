@props([
  'range',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.admin.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-range-name"
  :deleteConfirmValue="$range->name"
  containerClass="attack-range-card"
  :title="$range->name"
  :hasDetails="$range->description ? true : false"
>
  <x-slot:badge>
    @if($range->icon)
      <div class="range-icon">
        <img src="{{ asset('storage/' . $range->icon) }}" alt="{{ $range->name }}">
      </div>
    @else
      <div class="icon-badge" style="background-color: {{ $range->color ?? '#111111' }}">
        {{ strtoupper(substr($range->name, 0, 1)) }}
      </div>
    @endif
  </x-slot:badge>

  <div class="range-summary">
    <div class="stat-item-grid">
      <x-common.stat-item icon="heroes" :count="$range->abilities_count ?? 0" label="ataques" />
    </div>
    
    @if($range->icon)
      <div class="range-icon-preview">
        <img src="{{ asset('storage/' . $range->icon) }}" alt="{{ $range->name }}">
      </div>
    @endif
  </div>
</x-cards.admin.entity-card>