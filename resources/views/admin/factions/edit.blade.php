@extends('layouts.admin')

@section('title', 'Editar Facción')

@section('header-title', 'Editar Facción')

@section('content')
<div class="faction-form-container">
  <div class="header-actions-bar">
    <div class="left-actions">
      <h1>Editar Facción</h1>
      <p>Modifica los detalles de la facción "{{ $faction->name }}"</p>
    </div>
    <div class="right-actions">
      <a href="{{ route('admin.factions.index') }}" class="btn btn-secondary">
        <span>Volver al listado</span>
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

  <form action="{{ route('admin.factions.update', $faction) }}" method="POST" enctype="multipart/form-data" class="faction-form">
    @csrf
    @method('PUT')
    
    <div class="form-card">
      <div class="form-section">
        <div class="form-group">
          <label for="name" class="form-label">Nombre de la Facción <span class="required">*</span></label>
          <input 
            type="text" 
            id="name" 
            name="name" 
            class="form-input @error('name') is-invalid @enderror" 
            value="{{ old('name', $faction->name) }}" 
            required
          >
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="lore_text" class="form-label">Descripción / Lore</label>
          <textarea 
            id="lore_text" 
            name="lore_text" 
            class="form-textarea @error('lore_text') is-invalid @enderror" 
            rows="5"
          >{{ old('lore_text', $faction->lore_text) }}</textarea>
          @error('lore_text')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="color" class="form-label">Color <span class="required">*</span></label>
          <div class="color-input-group">
            <input 
              type="color" 
              id="color" 
              name="color" 
              class="form-color-input @error('color') is-invalid @enderror" 
              value="{{ old('color', $faction->color) }}" 
              required
            >
            <input 
              type="text" 
              id="color_text" 
              class="form-input color-text-input" 
              value="{{ old('color', $faction->color) }}" 
              readonly
            >
          </div>
          <small class="form-text">Selecciona un color representativo para la facción</small>
          @error('color')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <x-image-uploader
          name="icon" 
          label="Icono" 
          :currentImage="$faction->icon"
          acceptFormats="image/jpeg,image/png,image/gif,image/svg+xml"
        />
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('admin.factions.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>
    </div>
  </form>
</div>
@endsection