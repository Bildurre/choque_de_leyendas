@php
  $submitRoute = isset($block) 
    ? route('admin.pages.blocks.update', [$page, $block]) 
    : route('admin.pages.blocks.store', $page);
  $submitMethod = isset($block) ? 'PUT' : 'POST';
  $submitLabel = isset($block) ? __('admin.update') : __('admin.create');
@endphp

<form action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data" class="form">
  @csrf
  @if($submitMethod === 'PUT')
    @method('PUT')
  @endif

  @if(!isset($block))
    <input type="hidden" name="type" value="{{ $type }}">
  @endif
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.pages.edit', $page)">
    <x-slot:header>
      <h2>{{ __('blocks.form_title') }}: {{ $page->title }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="title"
          :label="__('blocks.title')"
          :values="old('title', isset($block) ? $block->getTranslations('title') : [])"
        />
        
        <x-form.multilingual-input
          name="subtitle"
          :label="__('blocks.subtitle')"
          :values="old('subtitle', isset($block) ? $block->getTranslations('subtitle') : [])"
        />
        
        @if((!isset($block) && $type === 'text') || (isset($block) && $block->type === 'text'))
          <x-form.multilingual-wysiwyg
            name="content"
            :label="__('blocks.content')"
            :values="old('content', isset($block) ? $block->getTranslations('content') : [])"
            :upload-url="route('admin.content.images.store')"
            :images-url="route('admin.content.images.index')"
            required
          />
        @endif
      </div>
      
      <div>
        <fieldset class="form-fieldset">
          <legend>{{ __('blocks.appearance') }}</legend>
          
          <x-form.select
            name="background_color"
            :label="__('blocks.background_color')"
            :options="config('blocks.background_colors')"
            :selected="old('background_color', isset($block) ? $block->background_color : 'none')"
          />
          
          @if($allowsImage ?? true)
            <x-form.image-upload
              name="image"
              :label="__('blocks.image')"
              :current-image="isset($block) && $block->image ? $block->getImageUrl() : null"
              :remove-name="isset($block) ? 'remove_image' : null"
            />
            
            @if(isset($blockConfig['settings']['image_position']))
              <x-form.select
                name="settings[image_position]"
                :label="__('blocks.settings.image_position')"
                :options="collect($blockConfig['settings']['image_position']['options'])->mapWithKeys(function($option) {
                  return [$option => __('blocks.settings.image_position_options.' . $option)];
                })->toArray()"
                :selected="old('settings.image_position', 
                  isset($block) && isset($block->settings['image_position']) 
                    ? $block->settings['image_position'] 
                    : ($blockConfig['settings']['image_position']['default'] ?? 'top')
                )"
              />
            @endif
          @endif
          
          @if(isset($blockConfig['settings']))
            @foreach($blockConfig['settings'] as $settingKey => $setting)
              @if($setting['type'] === 'boolean')
                <x-form.checkbox
                  name="settings[{{ $settingKey }}]"
                  :label="__('blocks.settings.' . $settingKey)"
                  :checked="old('settings.' . $settingKey, 
                    isset($block) && isset($block->settings[$settingKey]) 
                      ? $block->settings[$settingKey] 
                      : ($setting['default'] ?? false)
                  )"
                />
              @elseif($setting['type'] === 'select')
                <x-form.select
                  name="settings[{{ $settingKey }}]"
                  :label="__('blocks.settings.' . $settingKey)"
                  :options="collect($setting['options'])->mapWithKeys(function($option, $key) use ($settingKey) {
                    return [$key => __('blocks.settings.' . $settingKey . '_options.' . $key)];
                  })->toArray()"
                  :selected="old('settings.' . $settingKey, 
                    isset($block) && isset($block->settings[$settingKey]) 
                      ? $block->settings[$settingKey] 
                      : ($setting['default'] ?? null)
                  )"
                />
              @endif
            @endforeach
          @endif
        </fieldset>
      </div>
    </div>
  </x-form.card>
</form>