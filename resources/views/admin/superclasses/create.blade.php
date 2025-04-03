// resources/views/admin/superclasses/create.blade.php
@extends('layouts.admin')

@section('title', 'Nueva Superclase')

@section('header-title', 'Crear Superclase')

@section('content')
<div class="superclass-form-container">
  <div class="header-actions-bar">
    <div class="left-actions">
      <h1>Nueva Superclase</h1>
      <p>Crea una nueva superclase para los héroes</p>
    </div>
    <div class="right-actions">
      <a href="{{ route('admin.superclasses.index') }}" class="btn btn-secondary">
        <span>Volver al listado</span>
      </a>
    </div>
  </div>

  <form action="{{ route('admin.superclasses.store') }}" method="POST" class="superclass-form">
    @csrf
    
    <div class="form-card">
      <div class="form-section">
        <div class="form-group">
          <label for="name" class="form-label">Nombre de la Superclase <span class="required">*</span></label>
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
          <label for="description" class="form-label">Descripción</label>
          <textarea 
            id="description" 
            name="description" 
            class="form-textarea @error('description') is-invalid @enderror" 
            rows="4"
          >{{ old('description') }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear Superclase</button>
        <a href="{{ route('admin.superclasses.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>
    </div>
  </form>
</div>
@endsection