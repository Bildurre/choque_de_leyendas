@extends('admin.layouts.page', [
  'title' => 'Habilidades de Héroe',
  'headerTitle' => 'Gestión de Habilidades',
  'containerTitle' => 'Habilidades de Héroe',
  'subtitle' => 'Gestión de habilidades para los héroes',
  'createRoute' => route('admin.hero-abilities.create'),
  'createLabel' => '+ Nueva Habilidad'
])

@section('page-content')
  <x-entities-grid 
    empty_message="No hay habilidades disponibles"
    :create_route="route('admin.hero-abilities.create')"
    create_label="Crear la primera habilidad"
  >
    @foreach($heroAbilities as $ability)
      <x-game.ability-card 
        :ability="$ability"
        :showRoute="route('admin.hero-abilities.show', $ability)"
        :editRoute="route('admin.hero-abilities.edit', $ability)"
        :deleteRoute="route('admin.hero-abilities.destroy', $ability)"
      />
    @endforeach
  </x-entities-grid>
  
  <div class="pagination-container">
    {{ $heroAbilities->links() }}
  </div>
@endsection