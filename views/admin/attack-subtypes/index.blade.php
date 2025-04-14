@extends('admin.layouts.page', [
  'title' => 'Subtipos de Ataque',
  'headerTitle' => 'Gestión de Subtipos',
  'containerTitle' => 'Subtipos de Ataque',
  'subtitle' => 'Gestión de subtipos para categorizar ataques y habilidades',
  'createRoute' => route('admin.attack-subtypes.create'),
  'createLabel' => '+ Nuevo Subtipo'
])

@section('page-content')
  <x-common.entities-grid 
    empty_message="No hay subtipos de ataque disponibles"
    :create_route="route('admin.attack-subtypes.create')"
    create_label="Crear el primer subtipo"
  >
    @foreach($attackSubtypes as $subtype)
      <x-cards.admin.attack-subtype-card 
        :subtype="$subtype"
        :editRoute="route('admin.attack-subtypes.edit', $subtype)"
        :deleteRoute="route('admin.attack-subtypes.destroy', $subtype)"
      />
    @endforeach
  </x-common.entities-grid>
@endsection