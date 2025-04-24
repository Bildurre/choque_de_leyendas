@props(['cardType' => null, 'availableSuperclasses' => [], 'submitLabel' => 'Guardar', 'cancelRoute' => null])

<form 
  action="{{ $cardType ? route('admin.card-types.update', $cardType) : route('admin.card-types.store') }}" 
  method="POST" 
  class="card-type-form"
>
  @csrf
  @if($cardType) @method('PUT') @endif
  
  <x-form.card 
    :submit_label="$submitLabel"
    :cancel_route="$cancelRoute"
  >
    <x-form.field 
      name="name" 
      label="Nombre del Tipo de Carta" 
      :value="$cardType->name ?? ''" 
      required
      max="255" 
    />
    
    <x-form.select
      name="hero_superclass_id" 
      label="Superclase" 
      placeholder="Selecciona una superclase (opcional)"
      :value="$cardType->hero_superclass_id ?? ''"
      :options="$availableSuperclasses->pluck('name', 'id')->toArray()"
    />
    
    @if($cardType && $cardType->heroSuperclass)
      <div class="form-text">
        Este tipo de carta está asignado actualmente a la superclase <strong>{{ $cardType->heroSuperclass->name }}</strong>.
      </div>
    @endif
  </x-form.card>
</form>