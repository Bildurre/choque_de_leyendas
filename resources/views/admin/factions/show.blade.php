@extends('admin.layouts.page', [
  'title' => $faction->name,
  'headerTitle' => 'Detalle de Facción',
  'containerTitle' => $faction->name,
  'subtitle' => "Información detallada de la facción",
  'createRoute' => route('admin.factions.create'),
  'createLabel' => '+ Nueva Facción',
  'backRoute' => route('admin.factions.index')
])

@section('page-content')
  <div class="faction-detail-container">
    <div class="faction-detail-card">
      <div class="faction-header" style="border-color: {{ $faction->color }}">
        <div class="faction-title">
          <h2>{{ $faction->name }}</h2>
        </div>
        
        <div class="faction-actions">
          <a href="{{ route('admin.factions.edit', $faction) }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 4 23l-1-3.5L17 3z"></path></svg>
            Editar
          </a>
          
          <form action="{{ route('admin.factions.destroy', $faction) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger delete-btn" data-entity-name="{{ $faction->name }}" data-entity-type="facción">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
              Eliminar
            </button>
          </form>
        </div>
      </div>
      
      <div class="faction-body">
        <div class="faction-info-grid">
          <div class="faction-info-section">
            <h3>Información General</h3>
            <div class="faction-info-content">
              <div class="faction-info-item">
                <span class="info-label">Color:</span>
                <span class="info-value color-sample" style="background-color: {{ $faction->color }}"></span>
              </div>
              
              @if($faction->icon)
                <div class="faction-info-item">
                  <span class="info-label">Icono:</span>
                  <div class="faction-icon-container">
                    <img src="{{ asset('storage/' . $faction->icon) }}" alt="{{ $faction->name }}" class="faction-icon">
                  </div>
                </div>
              @endif
            </div>
          </div>
          
          <div class="faction-stats-section">
            <h3>Estadísticas</h3>
            <div class="faction-stats-grid">
              <div class="faction-stat-item">
                <span class="stat-value">{{ $faction->heroes_count ?? 0 }}</span>
                <span class="stat-label">{{ Str::plural('Héroe', $faction->heroes_count ?? 0) }}</span>
              </div>
              
              <div class="faction-stat-item">
                <span class="stat-value">{{ $faction->cards_count ?? 0 }}</span>
                <span class="stat-label">{{ Str::plural('Carta', $faction->cards_count ?? 0) }}</span>
              </div>
            </div>
          </div>
        </div>
        
        @if($faction->lore_text)
          <div class="faction-lore-section">
            <h3>Historia y Lore</h3>
            <div class="faction-lore-content">
              <p>{{ $faction->lore_text }}</p>
            </div>
          </div>
        @endif
        
        @if($faction->heroes && $faction->heroes->count() > 0)
          <div class="faction-heroes-section">
            <h3>Héroes de la Facción</h3>
            <div class="faction-heroes-grid">
              @foreach($faction->heroes as $hero)
                <div class="faction-hero-card">
                  <span class="hero-name">{{ $hero->name }}</span>
                  @if($hero->heroClass)
                    <span class="hero-class">{{ $hero->heroClass->name }}</span>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        @endif
        
        @if($faction->cards && $faction->cards->count() > 0)
          <div class="faction-cards-section">
            <h3>Cartas de la Facción</h3>
            <div class="faction-cards-stats">
              <canvas 
                id="factionCardsChart" 
                width="400" 
                height="200"
                data-chart="{{ json_encode($faction->getCardTypesChartData()) }}"
              ></canvas>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection