@php
  $submitRoute = isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store');
  $submitMethod = isset($page) ? 'PUT' : 'POST';
  $submitLabel = isset($page) ? __('admin.update') : __('pages.create');
  $showCancelButton = $show_cancel_button ?? true;
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif
  
  <x-form.card
    :submit_label="$submitLabel" 
    :cancel_route="$showCancelButton ? route('admin.pages.index') : null"
    :cancel_label="__('admin.cancel')"
  >
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

        <div id="order-field-container" style="{{ old('parent_id', isset($page) ? $page->parent_id : null) ? '' : 'display: none;' }}">
          <x-form.input
            type="number"
            name="order"
            :label="__('pages.order')"
            :value="old('order', isset($page) ? $page->order : 0)"
            min="0"
          />
        </div>
        
        <x-form.checkbox
          name="is_published"
          :label="__('pages.published')"
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
          
        <x-form.image-upload
          name="background_image"
          :label="__('pages.background_image')"
          :current-image="isset($page) && $page->background_image ? asset('storage/' . $page->background_image) : null"
          :remove-name="'remove_background_image'"
        />
      </div>
    </div>
  </x-form.card>
</form>