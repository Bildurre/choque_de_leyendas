@props([
    'name' => '',
    'description' => '',
    'variant' => 'active', // active, passive
    'cost' => null,
    'attackRange' => null,
    'attackSubtype' => null,
    'area' => false
])

<div class="ability-item ability-item--{{ $variant }}">
    @if($variant === 'active')
        <div class="ability-header">
            <div class="ability-info">
                <h4 class="ability-name">{{ $name }}</h4>
                @if($attackRange && $attackSubtype)
                    <div class="ability-types">
                        {{ $attackRange->name }} - 
                        {{ __('entities.attack_subtypes.types.' . $attackSubtype->type) }} - 
                        {{ $attackSubtype->name }}
                        @if($area)
                            - {{ __('entities.hero_abilities.area') }}
                        @endif
                    </div>
                @endif
            </div>
            @if($cost)
                <div class="ability-cost">
                    <x-cost-display :cost="$cost" />
                </div>
            @endif
        </div>
    @else
        <h4 class="ability-name">{{ $name }}</h4>
    @endif
    <div class="ability-description">{!! $description !!}</div>
</div>