@extends('layouts.admin')

@section('title', 'Nueva Clase de Héroe')

@section('header-title', 'Crear Clase de Héroe')

@section('content')
<div class="hero-class-form-container">
  <div class="header-actions-bar">
    <div class="left-actions">
      <h1>Nueva Clase</h1>
      <p>Crea una nueva clase para los héroes</p>
    </div>
    <div class="right-actions">
      <a href="{{ route('admin.hero-classes.index') }}" class="btn btn-secondary">
        <span>Volver al listado</span>
      </a>
    </div>
  </div>

  <form action="{{ route('admin.hero-classes.store') }}" method="POST" class="hero-class-form">
    @csrf
    
    <div class="form-card">
      <div class="form-section">
        <div class="form-group">
          <label for="name" class="form-label">Nombre de la Clase <span class="required">*</span></label>
          <input 
            type="text" 
            id="name" 
            name="name" 
            class="form-input @error('name') is-invalid @enderror" 
            value="{{ old('name') }}" 
            required
            maxlength="255"
          >
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="superclass_id" class="form-label">Superclase <span class="required">*</span></label>
          <select 
            id="superclass_id" 
            name="superclass_id" 
            class="form-input @error('superclass_id') is-invalid @enderror" 
            required
          >
            <option value="">Selecciona una superclase</option>
            @foreach($superclasses as $superclass)
              <option value="{{ $superclass->id }}" {{ old('superclass_id') == $superclass->id ? 'selected' : '' }}>
                {{ $superclass->name }}
              </option>
            @endforeach
          </select>
          @error('superclass_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="passive" class="form-label">Habilidad Pasiva</label>
          <textarea 
            id="passive" 
            name="passive" 
            class="form-textarea @error('passive') is-invalid @enderror" 
            rows="4"
          >{{ old('passive') }}</textarea>
          @error('passive')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="attribute-modifiers-section">
          <h3>Modificadores de Atributos</h3>
          <p class="form-text">Ajusta los modificadores de atributos. El total de modificadores debe ser entre -3 y +3.</p>
          
          @php
            $attributes = [
              'agility' => 'Agilidad',
              'mental' => 'Mental',
              'will' => 'Voluntad', 
              'strength' => 'Fuerza',
              'armor' => 'Armadura'
            ];
          @endphp

          <div class="attribute-modifiers-grid">
            @foreach($attributes as $key => $label)
              <div class="form-group">
                <label for="{{ $key }}_modifier" class="form-label">
                  {{ $label }}
                </label>
                <input 
                  type="number" 
                  id="{{ $key }}_modifier" 
                  name="{{ $key }}_modifier" 
                  class="form-input @error($key . '_modifier') is-invalid @enderror" 
                  value="{{ old($key . '_modifier', 0) }}" 
                  min="-2" 
                  max="2"
                  required
                >
                @error($key . '_modifier')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            @endforeach
          </div>
        </div>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear Clase</button>
        <a href="{{ route('admin.hero-classes.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>
    </div>
  </form>
</div>
@endsection