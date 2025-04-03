// resources/views/admin/superclasses/edit.blade.php
@extends('layouts.admin')

@section('title', 'Editar Superclase')

@section('header-title', 'Editar Superclase')

@section('content')
<div class="superclass-form-container">
  <div class="header-actions-bar">
    <div class="left-actions">
      <h1>Editar Superclase</h1>
      <p>Modifica los detalles de la superclase "{{ $superclass->name }}"</p>
    </div>
    <div class="right-actions">
      <a href="{{ route('admin.superclasses.index') }}" class="btn btn-secondary">
        <span>Volver al listado</span>
      </a>
    </div>
  </div>

  <form action="{{ route('admin.superclasses.update', $superclass) }}" method="POST" class="superclass-form">
    @csrf
    @method('PUT')
    
    <div class="form-card">
      <div class="form-section">
        <div class="form-group">
          <label for="name" class="form-label">Nombre de la Superclase <span class="required">*</span></label>
          <input 
            type="text" 
            id="name" 
            name="name" 
            class="form-input @error('name') is-invalid @enderror" 
            value="{{ old('name', $superclass->name) }}" 
            required
            maxlength="255"
          >
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="form-label">Descripción</label>
          <textarea 
            id="description" 
            name="description" 
            class="form-textarea @error('description') is-invalid @enderror" 
            rows="4"
          >{{ old('description', $superclass->description) }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('admin.superclasses.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>
    </div>
  </form>
</div>
@endsection