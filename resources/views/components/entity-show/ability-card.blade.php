@props([
    'name' => '',
    'description' => '',
    'variant' => 'active', // active, passive
    'cost' => null,
    'attackRange' => null,
    'attackType' => null,
    'attackSubtype' => null,
    'area' => false
])

<div class="ability-item ability-item--{{ $variant }}">
    @if($variant === 'active')
      <div class="ability-header">
        <h4 class="ability-name">{{ $name }}</h4>

        <div class="ability-cost">
          @if($cost)
            <x-cost-display :cost="$cost" />
          @endif
        </div>

        @if($attackRange && $attackSubtype)
          <div class="ability-types">
            {{ $attackRange->name }} - 
            {{ __('entities.attack_subtypes.types.' . $attackType) }} - 
            {{ $attackSubtype->name }}
            @if($area)
              - {{ __('entities.hero_abilities.area') }}
            @endif
          </div>
        @endif
      </div>
    @else
      <h4 class="ability-name">{{ $name }}</h4>
    @endif
    <div class="ability-description">{!! $description !!}</div>
</div>