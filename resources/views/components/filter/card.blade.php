<!-- resources/views/components/filters/card.blade.php -->
@props([
  'model',
  'request'
])

<div class="filters-card">
  <x-collapsible-section 
    id="filters-section" 
    :title="__('admin.filters.title')"
    :collapsed="session('filters_collapsed', false)"
  >
    <form action="{{ url()->current() }}" method="GET" class="filters-form">
      <!-- Search Input -->
      <div class="filters-search">
        <x-form.input
          type="text"
          name="search"
          :label="__('admin.filters.search')"
          :value="$request->search ?? ''"
          :placeholder="__('admin.filters.search_placeholder')"
        />
      </div>

      <!-- Sorting Buttons -->
      <x-filter.sort-group :model="$model" :request="$request" />

      <!-- Filters Select Group -->
      <x-filter.filter-select-group :model="$model" :request="$request" />
      
      <!-- Form Actions -->
      <div class="filters-actions">
        <x-button
          type="submit"
          variant="primary"
          size="md"
        >
          {{ __('admin.filters.apply') }}
        </x-button>
        
        <x-button-link
          :href="url()->current()"
          variant="secondary"
          size="md"
        >
          {{ __('admin.filters.reset') }}
        </x-button-link>
      </div>
    </form>
  </x-collapsible-section>
</div>