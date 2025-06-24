@props([
  'model',
  'request',
  'itemsCount' => 0,
  'totalCount' => 0,
  'filteredCount' => 0,
  'context' => 'admin' // 'admin' or 'public'
])

@php
  $isPublic = $context === 'public';
  $translationPrefix = $isPublic ? 'public.filters' : 'admin.filters';
@endphp

<div class="filters-card">
  <x-collapsible-section 
    id="filters-section" 
    :title="__($translationPrefix . '.title')"
    :collapsed="session('filter_collapsed', false)"
    :forceCollapse="true"
  >
    <form action="{{ url()->current() }}" method="GET" class="filters-form">
      <!-- Search Input and Apply Button -->
      <div class="filters-search-row">
        <div class="filters-search">
          <x-form.input
            type="text"
            name="search"
            :label="__($translationPrefix . '.search')"
            :value="$request->search ?? ''"
            :placeholder="__($translationPrefix . '.search_placeholder')"
          />
        </div>
      </div>
      
      <!-- Sorting Buttons -->
      <x-filters.sort-group :model="$model" :request="$request" :context="$context" />
      
      <!-- filters Select Group -->
      <x-filters.filters-select-group 
        :model="$model" 
        :request="$request" 
        :context="$context"
      />
      
      <div class="filters-actions">
        <x-button
          type="submit"
          variant="primary"
          size="md"
          icon="search"
        >
          {{ __($translationPrefix . '.apply') }}
        </x-button>
        
        @if($request->has('search') || count(array_filter($request->except(['page', 'sort', 'direction']))) > 0)
          <x-button-link
            href="{{ url()->current() }}"
            variant="secondary"
            size="md"
            icon="x-circle"
          >
            {{ __($translationPrefix . '.clear') }}
          </x-button-link>
        @endif
      </div>
    </form>
  </x-collapsible-section>
</div>