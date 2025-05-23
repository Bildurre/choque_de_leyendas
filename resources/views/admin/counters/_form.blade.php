@php
  $submitRoute = isset($counter) 
    ? route('admin.counters.update', $counter) 
    : route('admin.counters.store');
  $submitMethod = isset($counter) ? 'PUT' : 'POST';
  $submitLabel = isset($counter) ? __('admin.update') : __('entities.counters.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.counters.index', ['type' => isset($counter) ? $counter->type : $type])">
    <div class="form-grid">
      <x-form.multilingual-input
        name="name"
        :label="__('entities.counters.name')"
        :values="isset($counter) ? $counter->getTranslations('name') : []"
        required
      />
      
      <x-form.select
        name="type"
        :label="__('entities.counters.type')"
        :options="$types"
        :selected="old('type', isset($counter) ? $counter->type : $type)"
        required
      />
      
      <x-form.multilingual-wysiwyg
        name="effect"
        :label="__('entities.counters.effect')"
        :values="isset($counter) ? $counter->getTranslations('effect') : []"
      />
      
      <x-form.image-upload
        name="icon"
        :label="__('entities.counters.icon')"
        :current-image="isset($counter) && $counter->icon ? $counter->getImageUrl() : null"
        :remove-name="isset($counter) ? 'remove_icon' : null"
      />

      <x-form.checkbox
        name="is_published"
        :label="__('admin.published')"
        :checked="old('is_published', isset($counter) ? $counter->is_published : false)"
      />
    </div>
  </x-form.card>
</form>