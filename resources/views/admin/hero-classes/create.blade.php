@extends('layouts.admin')

@section('title', 'Nueva Clase de Héroe')

@section('header-title', 'Crear Clase de Héroe')

@section('content')
<div class="hero-class-form-container">
  <x-header-actions-bar 
    title="Crear Clase"
    subtitle="Crea los detalles de una nueva clase"
    :back_route="route('admin.hero-classes.index')"
  />

  <form action="{{ route('admin.hero-classes.store') }}" method="POST" class="hero-class-form">
    @csrf
    
    <x-form-card submit_label="Crear Clase"
    :cancel_route="route('admin.hero-classes.index')">
      <div class="form-section">
        <x-form.group name="name" label="Nombre de la Clase" :required="true">
          <x-form.input 
            name="name" 
            :value="$heroClass->name ?? ''" 
            :required="true" 
            maxlength="255" 
          />
        </x-form.group>

        <x-form.group name="superclass_id" label="Superclase" :required="true">
          <x-form.select 
            name="superclass_id" 
            :value="$heroClass->superclass_id ?? ''" 
            :required="true"
            :options="$superclasses->pluck('name', 'id')->toArray()"
          />
        </x-form.group>

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
          
          <div class="attribute-modifiers-grid">
            @foreach(['agility' => 'Agilidad', 'mental' => 'Mental', 'will' => 'Voluntad', 'strength' => 'Fuerza', 'armor' => 'Armadura'] as $key => $label)
              <x-form.group name="{{ $key }}_modifier" label="{{ $label }}">
                <x-form.input 
                  type="number" 
                  name="{{ $key }}_modifier" 
                  :value="$heroClass->{$key . '_modifier'} ?? 0" 
                  :required="true" 
                  min="-2" 
                  max="2" 
                />
              </x-form.group>
            @endforeach
          </div>
        </div>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Crear Clase</button>
        <a href="{{ route('admin.hero-classes.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>
    </x-form-card>
  </form>
</div>
@endsection