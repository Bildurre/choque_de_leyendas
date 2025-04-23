@props(['card'])

<x-preview-card
  :name="$card->name"
  :background-image="$card->image ? asset('storage/' . $card->image) : ''"
  :header-text-left="$card->type ?? ''"
  :header-text-right="$card->subtype ?? ''"
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
    <div class="preview-card-effect">
      {!! $card->effect !!}
    </div>
  </x-slot>
</x-preview-card>