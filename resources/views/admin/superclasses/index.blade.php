// resources/views/admin/superclasses/index.blade.php
@extends('layouts.admin')

@section('title', 'Superclases')

@section('header-title', 'Gestión de Superclases')

@section('content')
<div class="superclasses-container">
  <x-header-actions-bar 
    title="Superclases"
    subtitle="Gestión de superclases para los héroes"
    :create_route="route('admin.superclasses.create')"
    create_label="+ Nueva Superclase"
  />

  @if(session('success'))
    <x-alert type="success">
      {{ session('success') }}
    </x-alert>
  @endif

  @if(session('error'))
    <x-alert type="danger">
      {{ session('error') }}
    </x-alert>
  @endif

  <div class="entities-grid">
    @forelse($superclasses as $superclass)
      <x-game.superclass-card 
        :superclass="$superclass"
        :editRoute="route('admin.superclasses.edit', $superclass)"
        :deleteRoute="route('admin.superclasses.destroy', $superclass)"
      />
    @empty
    <x-no-entities 
      message="No hay superclases disponibles"
      :create_route="route('admin.superclasses.create')"
      create_label="Crear la primera superclase"
    />
    @endforelse
  </div>
</div>
@endsection