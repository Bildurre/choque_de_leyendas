@extends('admin.layouts.page', [
  'title' => 'Crear Clase',
  'headerTitle' => 'Crear Clase de Héroe',
  'containerTitle' => 'Clases',
  'subtitle' => 'Crea los detalles de una nueva clase',
  'createRoute' => route('admin.hero-classes.create'),
  'createLabel' => '+ Nueva Clase',
  'back_route' => 'route("admin.hero-classes.index")'
])

@section('page-content')
  <form action="{{ route('admin.hero-classes.store') }}" method="POST" class="hero-class-form">
    @csrf
    
    <x-form-card 
      submit_label="Crear Clase"
      :cancel_route="route('admin.hero-classes.index')"
    >
      <div class="form-section">
        <!-- Contenido del formulario -->
      </div>
    </x-form-card>
  </form>
@endsection