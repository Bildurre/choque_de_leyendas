@extends('layouts.admin')

@section('title', 'Editar Facción')

@section('header-title', 'Editar Facción')

@section('content')
<x-admin-container
  title="Editar Facción"
  subtitle="Modifica los detalles de la facción '{{ $faction->name }}'"
  :back_route="route('admin.factions.index')"
  back_label="Volver al listado"
>
  <form action="{{ route('admin.factions.update', $faction) }}" method="POST" enctype="multipart/form-data" class="faction-form">
    @csrf
    @method('PUT')
    
    <x-form-card 
      submit_label="Editar Facción"
      :cancel_route="route('admin.factions.index')"
    >
      <div class="form-section">
        <x-form.group name="name" label="Nombre de la Facción" :required="true">
          <x-form.input name="name" :value="$faction->name" :required="true" />
        </x-form.group>

        <x-form.group name="lore_text" label="Descripción / Lore">
          <x-form.textarea name="lore_text" :value="$faction->lore_text" rows="5" />
        </x-form.group>

        <x-form.group name="color" label="Color" :required="true">
          <x-form.color-input name="color" :value="$faction->color" :required="true" />
          <small class="form-text">Selecciona un color representativo para la facción</small>
        </x-form.group>

        <x-image-uploader
          name="icon" 
          label="Icono" 
          :currentImage="$faction->icon"
          acceptFormats="image/jpeg,image/png,image/gif,image/svg+xml"
        />
      </div>
    </x-form-card>
  </form>
</x-admin-container>
@endsection