@props(['equipmentType' => null, 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $equipmentType ? route('admin.equipment-types.update', $equipmentType) : route('admin.equipment-types.store') }}" 
  method="POST" 
  class="equipment-type-form"
>
  @csrf
  @if($equipmentType) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <div class="form-row">
      <x-form.field 
        name="name" 
        label="Nombre del Tipo de Equipo" 
        :value="$equipmentType->name ?? ''" 
        required
        max="255" 
      />
      
      <x-form.select
        name="category" 
        label="Categoría" 
        :value="$equipmentType->category ?? 'weapon'"
        :options="['weapon' => 'Arma', 'armor' => 'Armadura']"
        required
      />
    </div>
  </x-form.card>
</form>