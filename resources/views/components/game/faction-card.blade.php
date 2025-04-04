@props([
  'faction',
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null
])

<x-entity-card
  :borderColor="$faction->color"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="faction-name"
  :deleteConfirmValue="$faction->name"
  containerClass="faction-card"
>
  <div class="faction-card-content">
    <div class="faction-icon">
      @if($faction->icon)
        <img src="{{ asset('storage/' . $faction->icon) }}" alt="{{ $faction->name }}">
      @else
        <div class="faction-icon-placeholder" style="background-color: {{ $faction->color }}">
          {{ strtoupper(substr($faction->name, 0, 1)) }}
        </div>
      @endif
    </div>
    
    <div class="faction-info">
      <h3 class="faction-name">{{ $faction->name }}</h3>
      <p class="faction-color">
        <span class="color-dot" style="background-color: {{ $faction->color }}"></span>
        <span class="color-code">{{ $faction->color }}</span>
      </p>
    </div>
  </div>
  
  @if($showRoute)
    <x-slot:actions>
      <a href="{{ $showRoute }}" class="action-btn view-btn" title="Ver detalles">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
      </a>
    </x-slot:actions>
  @endif
</x-entity-card>