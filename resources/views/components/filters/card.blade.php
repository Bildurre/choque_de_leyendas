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
        
        @if(!$isPublic)
          <div class="filters-search-actions">
            <x-button
              type="submit"
              variant="primary"
              size="md"
            >
              {{ __($translationPrefix . '.apply') }}
            </x-button>
          </div>
        @endif
      </div>
      
      <!-- filters Select Group -->
      <x-filters.filters-select-group 
        :model="$model" 
        :request="$request" 
        :context="$context"
      />
      
      <!-- Sorting Buttons -->
      <x-filters.sort-group :model="$model" :request="$request" :context="$context" />
      
      @if($isPublic)
        <!-- Submit and Clear Buttons for Public -->
        <div class="filters-actions">
          <x-button
            type="submit"
            variant="primary"
            size="md"
          >
            {{ __($translationPrefix . '.apply') }}
          </x-button>
          
          @if($request->has('search') || count(array_filter($request->except(['page', 'sort', 'direction']))) > 0)
            <x-button-link
              href="{{ url()->current() }}"
              variant="secondary"
              size="md"
            >
              {{ __($translationPrefix . '.clear') }}
            </x-button-link>
          @endif
        </div>
      @endif
    </form>
  </x-collapsible-section>
  
  @if($filteredCount > 0 || $request->has('search') || $request->has('sort'))
    <div class="filters-results">
      @if($filteredCount == $totalCount)
        {{ __($translationPrefix . '.showing_all_results', ['count' => $filteredCount]) }}
      @else
        {{ __($translationPrefix . '.showing_filtered_results', ['count' => $filteredCount, 'total' => $totalCount]) }}
      @endif
      
      @if(!$isPublic && ($request->has('search') || count(array_filter($request->except(['page', 'sort', 'direction']))) > 0))
        <a href="{{ url()->current() }}" class="filters-results__clear">
          {{ __($translationPrefix . '.clear_filters') }}
          <x-icon name="x" size="sm" />
        </a>
      @endif
    </div>
  @endif
</div>