@extends('layouts.admin')

@section('title', 'Nueva Clase de Héroe')

@section('header-title', 'Crear Clase de Héroe')

@section('content')
<x-admin-container
  title="Crear Clase"
  subtitle="Crea los detalles de una nueva clase"
  :back_route="route('admin.hero-classes.index')"
>
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
</x-admin-container>
@endsection