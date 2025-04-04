@extends('layouts.admin')

@section('title', 'Editar Clase de Héroe')

@section('header-title', 'Editar Clase de Héroe')

@section('content')
<div class="hero-class-form-container">
  <x-header-actions-bar 
    title="Editar Clase"
    subtitle="Modifica los detalles de la clase '{{ $heroClass->name }}'"
    :back_route="route('admin.hero-classes.index')"
  />

  <form action="{{ route('admin.hero-classes.update', $heroClass) }}" method="POST" class="hero-class-form">
    @csrf
    @method('PUT')
    
    <x-form-card submit_label="Editar Clase"
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

        <x-form.group name="passive" label="Habilidad Pasiva">
          <x-form.textarea 
            name="passive" 
            :value="$heroClass->passive" 
            rows="4" 
          />
        </x-form.group>

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

<div class="entities-grid">
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
                  value="{{ old($key . '_modifier', $heroClass->{$key . '_modifier'}) }}" 
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
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('admin.hero-classes.index') }}" class="btn btn-secondary">Cancelar</a>
      </div>
    </x-form-card>
  </form>
</div>
@endsection