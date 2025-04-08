@extends('admin.layouts.page', [
  'title' => $heroAbility->name,
  'headerTitle' => 'Detalle de Habilidad',
  'containerTitle' => $heroAbility->name,
  'subtitle' => "Detalle completo de la habilidad",
  'createRoute' => route('admin.hero-abilities.create'),
  'createLabel' => '+ Nueva Habilidad',
  'backRoute' => route('admin.hero-abilities.index')
])

@section('page-content')
  <div class="ability-detail-container">
    <div class="ability-detail-card">
      <div class="ability-header" style="border-color: {{ $heroAbility->subtype ? $heroAbility->subtype->color : '#666666' }}">
        <div class="ability-title">
          <h2>{{ $heroAbility->name }}</h2>
          
          <div class="ability-meta">
            @if($heroAbility->is_passive)
              <span class="ability-badge passive-badge">Pasiva</span>
            @else
              <div class="ability-cost">
                <span class="cost-label">Coste:</span>
                <x-game.cost-display :cost="$heroAbility->cost" />
              </div>
            @endif
          </div>
        </div>
        
        <div class="ability-actions">
          <a href="{{ route('admin.hero-abilities.edit', $heroAbility) }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 4 23l-1-3.5L17 3z"></path></svg>
            Editar
          </a>
          
          <form action="{{ route('admin.hero-abilities.destroy', $heroAbility) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger delete-btn" data-ability-name="{{ $heroAbility->name }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
              Eliminar
            </button>
          </form>
        </div>
      </div>
      
      <div class="ability-body">
        <div class="ability-classification">
          <div class="classification-group">
            <h3>Tipo</h3>
            @if($heroAbility->subtype)
              <div class="type-badges">
                <span class="type-badge" style="background-color: {{ $heroAbility->subtype->type->color }}; color: {{ $heroAbility->subtype->type->text_is_dark ? '#000000' : '#ffffff' }}">
                  {{ $heroAbility->subtype->type->name }}
                </span>
                <span class="subtype-badge">
                  {{ $heroAbility->subtype->name }}
                </span>
              </div>
            @else
              <p>Sin tipo asignado</p>
            @endif
          </div>
          
          <div class="classification-group">
            <h3>Rango</h3>
            @if($heroAbility->range)
              <div class="range-badge">
                @if($heroAbility->range->icon)
                  <img src="{{ asset('storage/' . $heroAbility->range->icon) }}" alt="{{ $heroAbility->range->name }}" class="range-icon">
                @endif
                {{ $heroAbility->range->name }}
              </div>
            @else
              <p>Sin rango asignado</p>
            @endif
          </div>
        </div>
        
        <div class="ability-description-section">
          <h3>Descripción</h3>
          <div class="ability-description-content">
            {!! $heroAbility->description !!}
          </div>
        </div>
        
        <div class="ability-heroes-section">
          <h3>Héroes con esta habilidad</h3>
          @if($heroAbility->heroes->count() > 0)
            <div class="heroes-grid">
              @foreach($heroAbility->heroes as $hero)
                <div class="hero-card">
                  <div class="hero-name">{{ $hero->name }}</div>
                  @if($hero->pivot->is_default)
                    <span class="default-badge">Por defecto</span>
                  @endif
                </div>
              @endforeach
            </div>
          @else
            <p>No hay héroes asignados a esta habilidad.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection