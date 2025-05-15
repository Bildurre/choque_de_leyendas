@props([
  'model',
  'request',
  'itemsCount' => 0,
  'totalCount' => 0,
  'filteredCount' => 0
])

<div class="filters-card">
  <x-collapsible-section 
    id="filters-section" 
    :title="__('admin.filters.title')"
    :collapsed="session('filter_collapsed', false)"
  >
    <form action="{{ url()->current() }}" method="GET" class="filters-form">
      <!-- Search Input and Apply Button -->
      <div class="filters-search-row">
        <div class="filters-search">
          <x-form.input
            type="text"
            name="search"
            :label="__('admin.filters.search')"
            :value="$request->search ?? ''"
            :placeholder="__('admin.filters.search_placeholder')"
          />
        </div>
        
        <div class="filters-search-actions">
          <x-button
            type="submit"
            variant="primary"
            size="md"
          >
            {{ __('admin.filters.apply') }}
          </x-button>
        </div>
      </div>
      
      <!-- filters Select Group -->
      <x-filters.filters-select-group :model="$model" :request="$request" />
      
      <!-- Sorting Buttons -->
      <x-filters.sort-group :model="$model" :request="$request" />
    </form>
  </x-collapsible-section>
  
  @if($filteredCount > 0 || $request->has('search') || $request->has('sort'))
    <div class="filters-results">
      @if($filteredCount == $totalCount)
        {{ __('admin.filters.showing_all_results', ['count' => $filteredCount]) }}
      @else
        {{ __('admin.filters.showing_filtered_results', ['count' => $filteredCount, 'total' => $totalCount]) }}
      @endif
      
      @if($request->has('search') || count(array_filter($request->except(['page', 'sort', 'direction']))) > 0)
        <a href="{{ url()->current() }}" class="filters-results__clear">
          {{ __('admin.filters.clear_filters') }}
          <x-icon name="x" size="sm" />
        </a>
      @endif
    </div>
  @endif
</div>