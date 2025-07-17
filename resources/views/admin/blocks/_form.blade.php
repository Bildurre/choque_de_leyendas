@php
  $submitRoute = isset($block) 
    ? route('admin.pages.blocks.update', [$page, $block]) 
    : route('admin.pages.blocks.store', $page);
  $submitMethod = isset($block) ? 'PUT' : 'POST';
  $submitLabel = isset($block) ? __('admin.update') : __('admin.create');
  $blockType = isset($block) ? $block->type : $type;
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
        $blockName = __('pages.blocks.types.' . $blockType);
      @endphp
      <h2>{{ __('pages.blocks.form_title', ['block_name' => $blockName, 'page_title' => $page->title]) }}</h2>
    </x-slot:header>
    
    <div class="form-grid">
      <div>
        {{-- Common fields for all blocks --}}
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
        
        {{-- Block-specific content fields --}}
        @include('admin.blocks.partials._' . $blockType, [
          'block' => $block ?? null,
          'page' => $page
        ])
      </div>

      <div>
        <x-form.select
          name="parent_id"
          :label="__('pages.blocks.parent')"
          :options="$blocks"
          :selected="old('parent_id', isset($block) ? $block->parent_id : null)"
          :placeholder="__('pages.blocks.no_parent')"
        />

        <x-form.checkbox
          name="is_indexable"
          :label="__('pages.blocks.indexable')"
          :checked="old('is_indexable', isset($block) ? $block->is_indexable : true)"
        />
        
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
              : 'justify'
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

          <x-form.checkbox
            name="settings[limit_height]"
            :label="__('pages.blocks.image_limit_height')"
            :checked="old('settings.limit_height', $block->settings['limit_height'] ?? true)"
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
              '2-3' => '2:3',
              '4-1' => '4:1',
              '1-4' => '1:4',
              '4-3' => '4:3',
              '3-4' => '3:4',
            ]"
            :selected="old('settings.column_proportions', 
              isset($block) && isset($block->settings['column_proportions']) 
                ? $block->settings['column_proportions'] 
                : '1-1'
            )"
          />
        @endif
        
        {{-- Dynamic settings from config --}}
        @if(isset($blockConfig['settings']))
          @foreach($blockConfig['settings'] as $settingKey => $setting)
            {{-- Skip multilingual text settings that should be in content --}}
            @if($setting['type'] === 'text' && ($setting['multilingual'] ?? false))
              @continue
            @endif
            
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
            @elseif($setting['type'] === 'text' && !($setting['multilingual'] ?? false))
              <x-form.input
                type="text"
                name="settings[{{ $settingKey }}]"
                :label="__('pages.blocks.settings.' . $settingKey)"
                :value="old('settings.' . $settingKey, isset($block) && isset($block->settings[$settingKey]) ? $block->settings[$settingKey] : ($setting['default'] ?? ''))"
              />
            @endif
          @endforeach
        @endif
      </div>
    </div>
  </x-form.card>
</form>