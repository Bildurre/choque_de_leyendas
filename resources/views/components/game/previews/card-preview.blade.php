@props(['card'])

<x-previews.base-preview
  :name="$card->name"
  :background-image="$card->image ? asset('storage/' . $card->image) : ''"
  :header-text-left="($card->cardType?->name ?? '') . 
                    ($card->equipmentType ? (' - ' . $card->equipmentType->name . 
                      ($card->hands ? (' - ' . $card->hands . ' ' . ($card->hands == 1 ? 'Mano' : 'Manos')) : '')) : '')"
  :header-text-right="($card->is_attack ? 
                      (($card->attackRange?->name ?? '') . 
                       ($card->attackSubtype ? (' - ' . $card->attackSubtype->typeName) : '') .
                       ($card->attackSubtype ? (' - ' . $card->attackSubtype->name) : '') .
                       ($card->area ? ' - Área' : '')) : '')"
  :faction-color="$card->faction?->color ?? '#3d3df5'"
  :faction-text-is-dark="$card->faction?->text_is_dark ?? true"
  :faction-icon="$card->faction?->icon ? asset('storage/' . $card->faction->icon) : ''"
  {{ $attributes }}
>
  <x-slot name="sideInfo">
    <div class="preview-card-cost">
      <x-cost-display :cost="$card->cost"/>
    </div>
  </x-slot>

  <x-slot name="content">
    @if($card->restriction)
      <div class="preview-card-restriction">
        {!! $card->restriction !!}
      </div>
    @endif

    @if($card->effect)
      <div class="preview-card-effect">
        {!! $card->effect !!}
      </div>
    @endif

    @if($card->has_hero_ability && $card->heroAbility)
      <div class="preview-card-ability">
        {!! $card->heroAbility->description !!}
      </div>
    @endif
  </x-slot>
</x-previews.base-preview>