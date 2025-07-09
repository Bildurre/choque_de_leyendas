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
  
  {{-- Hidden field to ensure is_printable is always sent --}}
  <input type="hidden" name="is_printable" value="0">
  
  <x-form.card :submit_label="$submitLabel" :cancel_route="route('admin.pages.edit', $page)">
    <x-slot:header>
      @php
        $blockName = isset($block) 
          ? __('pages.blocks.types.' . $block->type) 
          : __('pages.blocks.types.' . $type);
      @endphp
      <h2>{{ __('pages.blocks.form_title', ['block_name' => $blockName, 'page_title' => $page->title]) }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        <x-form.multilingual-input
          name="title"
          :label="__('pages.blocks.title')"
          :values="old('title', isset($block) ? $block->getTranslations('title') : [])"
        />

        <x-form.multilingual-wysiwyg
          name="subtitle"
          :label="__('pages.blocks.subtitle')"
          :values="old('subtitle', isset($block) ? $block->getTranslations('subtitle') : [])"
        />
        
        @if((!isset($block) && $type === 'text') || (isset($block) && $block->type === 'text'))
          <x-form.multilingual-wysiwyg
            name="content"
            :label="__('pages.blocks.content')"
            :values="old('content', isset($block) ? $block->getTranslations('content') : [])"
            required
          />
        @endif
        
        @if((!isset($block) && $type === 'cta') || (isset($block) && $block->type === 'cta'))
          @php
            $contentData = isset($block) ? $block->getTranslations('content') : [];
            $textValues = [];
            $buttonTextValues = [];
            $buttonLinkValues = [];
            
            // Extraer los valores del content JSON
            foreach (config('laravellocalization.supportedLocales', ['es' => []]) as $locale => $localeData) {
              $textValues[$locale] = old("content.text.{$locale}", $contentData[$locale]['text'] ?? '');
              $buttonTextValues[$locale] = old("content.button_text.{$locale}", $contentData[$locale]['button_text'] ?? '');
              $buttonLinkValues[$locale] = old("content.button_link.{$locale}", $contentData[$locale]['button_link'] ?? '');
            }
          @endphp
          
          <x-form.multilingual-wysiwyg
            name="content[text]"
            :label="__('pages.blocks.cta_text')"
            :values="$textValues"
            type="textarea"
          />
          
          <x-form.multilingual-input
            name="content[button_text]"
            :label="__('pages.blocks.cta_button_text')"
            :values="$buttonTextValues"
          />
          
          <x-form.multilingual-input
            name="content[button_link]"
            :label="__('pages.blocks.cta_button_link')"
            :values="$buttonLinkValues"
          />
        @endif
      </div>

      <div>
        <x-form.select
            name="settings[text_alignment]"
            :label="__('pages.blocks.settings.text_alignment')"
            :options="[
              'justify' => __('pages.blocks.settings.text_alignment_options.justify'),
              'left' => __('pages.blocks.settings.text_alignment_options.left'),
              'right' => __('pages.blocks.settings.text_alignment_options.right'),
              'center' => __('pages.blocks.settings.text_alignment_options.center'),
            ]"
            :selected="old('settings.text_alignment', 
              isset($block) && isset($block->settings['text_alignment']) 
                ? $block->settings['text_alignment'] 
                : 'left'
            )"
          />

        <x-form.select
          name="background_color"
          :label="__('pages.blocks.background_color')"
          :options="config('blocks.background_colors', [])"
          :selected="old('background_color', isset($block) ? $block->background_color : 'none')"
        />
        
        <x-form.checkbox
          name="is_printable"
          :label="__('pages.blocks.printable')"
          :checked="old('is_printable', isset($block) ? $block->is_printable : true)"
          :help="__('pages.blocks.printable_help')"
        />
        
        @if((isset($blockConfig) && $blockConfig['allows_image']) || (isset($block) && $block->hasMultilingualImage()))
          <x-form.multilingual-image-upload
            name="image"
            :label="__('pages.blocks.image')"
            :current-images="isset($block) ? $block->image : []"
          />

         @php
            $options = [
              'left' => __('pages.blocks.image_position_options.left'),
              'right' => __('pages.blocks.image_position_options.right'),
              'top' => __('pages.blocks.image_position_options.top'),
              'bottom' => __('pages.blocks.image_position_options.bottom'),
            ];
            
            if (isset($blockConfig) && isset($blockConfig['allows_clearfix_image']) && $blockConfig['allows_clearfix_image']) {
              $options['clearfix-left'] = __('pages.blocks.image_position_options.clearfix_left');
              $options['clearfix-right'] = __('pages.blocks.image_position_options.clearfix_right');
            }
          @endphp

          <x-form.select
            name="settings[image_position]"
            :label="__('pages.blocks.image_position')"
            :options="$options"
            :selected="old('settings.image_position', 
              isset($block) && isset($block->settings['image_position']) 
                ? $block->settings['image_position'] 
                : 'left'
            )"
          />
          
          <x-form.select
            name="settings[image_scale_mode]"
            :label="__('pages.blocks.image_scale_mode')"
            :options="[
              'contain' => __('pages.blocks.image_scale_mode_options.contain'),
              'cover' => __('pages.blocks.image_scale_mode_options.cover'),
              'fill' => __('pages.blocks.image_scale_mode_options.fill'),
            ]"
            :selected="old('settings.image_scale_mode', 
              isset($block) && isset($block->settings['image_scale_mode']) 
                ? $block->settings['image_scale_mode'] 
                : 'contain'
            )"
          />
          
          <x-form.select
            name="settings[column_proportions]"
            :label="__('pages.blocks.column_proportions')"
            :options="[
              '1-1' => '1:1',
              '2-1' => '2:1',
              '1-2' => '1:2',
              '3-1' => '3:1',
              '1-3' => '1:3',
              '3-2' => '3:2',
              '2-3' => '2:3'
            ]"
            :selected="old('settings.column_proportions', 
              isset($block) && isset($block->settings['column_proportions']) 
                ? $block->settings['column_proportions'] 
                : '1-1'
            )"
          />
        @endif
        
        @if(isset($blockConfig['settings']))
          @foreach($blockConfig['settings'] as $settingKey => $setting)
            @if($setting['type'] === 'boolean')
              <x-form.checkbox
                name="settings[{{ $settingKey }}]"
                :label="__('pages.blocks.settings.' . $settingKey)"
                :checked="old('settings.' . $settingKey, 
                  isset($block) && isset($block->settings[$settingKey]) 
                    ? $block->settings[$settingKey] 
                    : ($setting['default'] ?? false)
                )"
              />
            @elseif($setting['type'] === 'select')
              <x-form.select
                name="settings[{{ $settingKey }}]"
                :label="__('pages.blocks.settings.' . $settingKey)"
                :options="collect($setting['options'])->mapWithKeys(function($option, $key) use ($settingKey) {
                  return [$key => __('pages.blocks.settings.' . $settingKey . '_options.' . $key)];
                })->toArray()"
                :selected="old('settings.' . $settingKey, 
                  isset($block) && isset($block->settings[$settingKey]) 
                    ? $block->settings[$settingKey] 
                    : ($setting['default'] ?? null)
                )"
              />
            @elseif($setting['type'] === 'text')
              <x-form.input
                type="text"
                name="settings[{{ $settingKey }}]"
                :label="__('pages.blocks.settings.' . $settingKey)"
                :value="old('settings.' . $settingKey, 
                  isset($block) && isset($block->settings[$settingKey]) 
                    ? $block->settings[$settingKey] 
                    : ($setting['default'] ?? '')
                )"
              />
            @endif
          @endforeach
        @endif
      </div>
    </div>
  </x-form.card>
</form>