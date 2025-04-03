@extends('layouts.admin')

@section('title', 'Facciones')

@section('header-title', 'Gestión de Facciones')

@section('content')
<div class="factions-container">
  <div class="header-actions-bar">
    <div class="left-actions">
      <h1>Facciones</h1>
      <p>Gestión de facciones del juego</p>
    </div>
    <div class="right-actions">
      <a href="{{ route('admin.factions.create') }}" class="btn-primary">
        <span>+ Nueva Facción</span>
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  <div class="factions-grid">
    @forelse($factions as $faction)
      <div class="faction-card" style="border-left: 4px solid {{ $faction->color }}">
        <div class="faction-card-content">
          <div class="faction-icon">
            @if($faction->icon)
              <img src="{{ asset('storage/' . $faction->icon) }}" alt="{{ $faction->name }}">
            @else
              <div class="faction-icon-placeholder" style="background-color: {{ $faction->color }}">
                {{ strtoupper(substr($faction->name, 0, 1)) }}
              </div>
            @endif
          </div>
          
          <div class="faction-info">
            <h3 class="faction-name">{{ $faction->name }}</h3>
            <p class="faction-color">
              <span class="color-dot" style="background-color: {{ $faction->color }}"></span>
              <span class="color-code">{{ $faction->color }}</span>
            </p>
          </div>
        </div>
        
        <div class="faction-actions">
          <a href="{{ route('admin.factions.show', $faction) }}" class="action-btn view-btn" title="Ver detalles">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>
          </a>
          <a href="{{ route('admin.factions.edit', $faction) }}" class="action-btn edit-btn" title="Editar">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"></path></svg>
          </a>
          <form action="{{ route('admin.factions.destroy', $faction) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="action-btn delete-btn" title="Eliminar" data-faction-id="{{ $faction->id }}" data-faction-name="{{ $faction->name }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
            </button>
          </form>
        </div>
      </div>
    @empty
      <div class="no-factions">
        <p>No hay facciones disponibles</p>
        <a href="{{ route('admin.factions.create') }}" class="btn-primary">Crear la primera facción</a>
      </div>
    @endforelse
  </div>
</div>
@endsection