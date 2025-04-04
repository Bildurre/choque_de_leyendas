@props(['modifiers' => []])

<div class="entities-grid">
  @foreach($modifiers as $label => $value)
    <x-game.attribute-modifier :label="$label" :value="$value" />
  @endforeach
</div>