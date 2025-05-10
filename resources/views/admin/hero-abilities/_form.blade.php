@php
  $submitRoute = isset($heroAbility) 
    ? route('admin.hero-abilities.update', $heroAbility) 
    : route('admin.hero-abilities.store');
  $submitMethod = isset($heroAbility) ? 'PUT' : 'POST';
  $submitLabel = isset($heroAbility) ? __('admin.update') : __('hero_abilities.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.hero-abilities.index')">
    <x-slot:header>
      <h2>{{ __('hero_abilities.form_title') }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="name"
          :label="__('hero_abilities.name')"
          :values="isset($heroAbility) ? $heroAbility->getTranslations('name') : []"
          required
        />
        
        <x-form.multilingual-wysiwyg
          name="description"
          :label="__('hero_abilities.description')"
          :values="isset($heroAbility) ? $heroAbility->getTranslations('description') : []"
          :upload-url="route('admin.content.images.store')"
          :images-url="route('admin.content.images.index')"
        />
      </div>
      
      <div>
        <x-form.cost-input
          name="cost"
          :label="__('hero_abilities.cost')"
          :value="old('cost', isset($heroAbility) ? $heroAbility->cost : '')"
          :help="__('hero_abilities.cost_help')"
          required
        />
        
        <div class="attack-container" id="attack-container">
          <x-form.select
            name="attack_range_id"
            :label="__('attack_ranges.singular')"
            :options="['' => __('hero_abilities.no_attack_range')] + $attackRanges->pluck('name', 'id')->toArray()"
            :selected="old('attack_range_id', isset($heroAbility) ? $heroAbility->attack_range_id : '')"
          />
          
          <x-form.select
            name="attack_subtype_id"
            :label="__('attack_subtypes.singular')"
            :options="['' => __('hero_abilities.no_attack_subtype')] + $attackSubtypes->pluck('name', 'id')->toArray()"
            :selected="old('attack_subtype_id', isset($heroAbility) ? $heroAbility->attack_subtype_id : '')"
          />
          
          <div id="area-container" style="display: none;">
            <x-form.checkbox
              name="area"
              :label="__('hero_abilities.is_area_attack')"
              :checked="old('area', isset($heroAbility) ? $heroAbility->area : false)"
            />
          </div>
        </div>
      </div>
    </div>
  </x-form.card>
</form>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const attackRangeSelect = document.getElementById('attack_range_id');
    const attackSubtypeSelect = document.getElementById('attack_subtype_id');
    const areaContainer = document.getElementById('area-container');
    
    // Función para actualizar la visibilidad del campo 'area'
    function updateAreaVisibility() {
      const attackRangeId = attackRangeSelect.value;
      const attackSubtypeId = attackSubtypeSelect.value;
      
      // Mostrar el campo 'area' solo si se han seleccionado tanto el rango como el subtipo
      if (attackRangeId !== '' && attackSubtypeId !== '') {
        areaContainer.style.display = 'block';
      } else {
        areaContainer.style.display = 'none';
      }
    }
    
    // Sincronizar los selectores de ataque
    function syncAttackSelects(triggered) {
      if (triggered === 'range') {
        // Si se seleccionó un rango pero no hay subtipo, alertar
        if (attackRangeSelect.value !== '' && attackSubtypeSelect.value === '') {
          alert("{{ __('hero_abilities.select_attack_subtype_warning') }}");
        }
      } else if (triggered === 'subtype') {
        // Si se seleccionó un subtipo pero no hay rango, alertar
        if (attackSubtypeSelect.value !== '' && attackRangeSelect.value === '') {
          alert("{{ __('hero_abilities.select_attack_range_warning') }}");
        }
      }
      
      updateAreaVisibility();
    }
    
    // Asignar eventos
    attackRangeSelect.addEventListener('change', function() {
      syncAttackSelects('range');
    });
    attackSubtypeSelect.addEventListener('change', function() {
      syncAttackSelects('subtype');
    });
    
    // Inicializar estado
    updateAreaVisibility();
  });
</script>
@endpush