@php
  $submitRoute = isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store');
  $submitMethod = isset($page) ? 'PUT' : 'POST';
  $submitLabel = isset($page) ? __('admin.update') : __('pages.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.pages.index')">
    <x-slot:header>
      <h2>{{ __('pages.form_title') }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="title"
          :label="__('pages.title')"
          :values="isset($page) ? $page->getTranslations('title') : []"
          required
        />
        
        <x-form.multilingual-wysiwyg
          name="description"
          :label="__('pages.description')"
          :values="isset($page) ? $page->getTranslations('description') : []"
        />

        <x-form.select
          name="template"
          :label="__('pages.template')"
          :options="$templates"
          :selected="old('template', isset($page) ? $page->template : 'default')"
          :placeholder="__('admin.select_option')"
        />
      </div>

      <div>
        <x-form.select
          name="parent_id"
          :label="__('pages.parent')"
          :options="$pages"
          :selected="old('parent_id', isset($page) ? $page->parent_id : null)"
          :placeholder="__('pages.no_parent')"
        />
        
        <x-form.input
          type="number"
          name="order"
          :label="__('pages.order')"
          :value="old('order', isset($page) ? $page->order : 0)"
          min="0"
        />
        
        <x-form.checkbox
          name="is_published"
          :label="__('pages.is_published')"
          :checked="old('is_published', isset($page) ? $page->is_published : false)"
        />
        
        <fieldset class="form-fieldset">
          <legend>{{ __('pages.meta_info') }}</legend>
          
          <x-form.multilingual-input
            name="meta_title"
            :label="__('pages.meta_title')"
            :values="isset($page) ? $page->getTranslations('meta_title') : []"
          />
          
          <x-form.multilingual-input
            name="meta_description"
            :label="__('pages.meta_description')"
            :values="isset($page) ? $page->getTranslations('meta_description') : []"
          />
        </fieldset>
        
        <fieldset class="form-fieldset">
          <legend>{{ __('pages.images') }}</legend>
          
          <x-form.image-upload
            name="image"
            :label="__('pages.image')"
            :current-image="isset($page) && $page->image ? $page->image_url : null"
            :remove-name="'remove_image'"
          />
          
          <x-form.image-upload
            name="background_image"
            :label="__('pages.background_image')"
            :current-image="isset($page) && $page->background_image ? $page->getBackgroundImageUrl() : null"
            :remove-name="'remove_background_image'"
          />
        </fieldset>
      </div>
    </div>
  </x-form.card>
</form>