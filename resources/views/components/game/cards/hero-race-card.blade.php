@props([
  'heroRace',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-game.cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="hero-race-name"
  :deleteConfirmValue="$heroRace->name"
  containerClass="hero-race-card"
  :title="$heroRace->name"
  :hasDetails="true"
>
  <div class="card-summary">
    <x-core.stat-item icon="heroes" :count="$heroRace->heroes_count ?? 0" label="héroe" />
  </div>
</x-game.cards.entity-card>