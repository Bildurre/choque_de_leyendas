<x-admin-layout>
  <x-admin.page-header :title="__('previews.title')">
    <x-slot:actions>
      <x-button-link
        :href="route('admin.dashboard')"
        variant="secondary"
        icon="arrow-left"
      >
        {{ __('admin.back_to_dashboard') }}
      </x-button-link>
    </x-slot:actions>
  </x-admin.page-header>
  
  <div class="page-content">
    {{-- Status Overview --}}
    <x-preview-management.section :title="__('previews.status_overview')">
      <x-preview-management.card :title="__('previews.heroes_status')">
        <x-preview-management.stat-item :label="__('previews.complete')" :value="$statusData['heroes']['complete']" />
        <x-preview-management.stat-item :label="__('previews.partial')" :value="$statusData['heroes']['partial']" />
        <x-preview-management.stat-item :label="__('previews.missing')" :value="$statusData['heroes']['missing']" />
        <x-preview-management.stat-item :label="__('previews.total')" :value="$statusData['heroes']['total']" />
        <x-preview-management.stat-item :label=" __('previews.disk_usage')" :value="$statusData['disk']['heroes']" variant="disk" />
      </x-preview-management.card>
      
      <x-preview-management.card :title="__('previews.cards_status')">
        <x-preview-management.stat-item :label="__('previews.complete')" :value="$statusData['cards']['complete']" />
        <x-preview-management.stat-item :label="__('previews.partial')" :value="$statusData['cards']['partial']" />
        <x-preview-management.stat-item :label="__('previews.missing')" :value="$statusData['cards']['missing']" />
        <x-preview-management.stat-item :label="__('previews.total')" :value="$statusData['cards']['total']" />
        <x-preview-management.stat-item :label=" __('previews.disk_usage')" :value="$statusData['disk']['cards']" variant="disk" />
      </x-preview-management.card>
      
      <x-preview-management.card :title="__('previews.total_disk_usage')">
        <x-preview-management.stat-item :label=" __('previews.total')" :value="$statusData['disk']['total']" variant="disk" />
      </x-preview-management.card>
    </x-preview-management.section>

    {{-- Bulk Actions Section --}}
    <x-preview-management.section :title="__('previews.bulk_actions')">
      <x-preview-management.card :title="__('previews.generation_actions')">
        <form action="{{ route('admin.previews.generate-all') }}" method="POST">
          @csrf
          <x-button type="submit" variant="primary" icon="plus-circle">
            {{ __('previews.generate_missing') }}
          </x-button>
        </form>
        
        <form action="{{ route('admin.previews.regenerate-all') }}" method="POST">
          @csrf
          <x-button type="submit" variant="info" icon="refresh" class="confirm-action" data-confirm="{{ __('previews.confirm_regenerate_all') }}">
            {{ __('previews.regenerate_all') }}
          </x-button>
        </form>
      </x-preview-management.card>

      <x-preview-management.card :title="__('previews.deletion_actions')">
        <div class="preview-management--wrapper">
          <form action="{{ route('admin.previews.delete-all-heroes') }}" method="POST">
            @csrf
            <x-button type="submit" variant="secondary" icon="trash" class="confirm-action" data-confirm="{{ __('previews.confirm_delete_all_heroes') }}">
              {{ __('previews.delete_heroes') }}
            </x-button>
          </form>
          
          <form action="{{ route('admin.previews.delete-all-cards') }}" method="POST">
            @csrf
            <x-button type="submit" variant="secondary" icon="trash" class="confirm-action" data-confirm="{{ __('previews.confirm_delete_all_cards') }}">
              {{ __('previews.delete_cards') }}
            </x-button>
          </form>
        </div>
        
        <form action="{{ route('admin.previews.delete-all') }}" method="POST">
          @csrf
          <x-button type="submit" variant="danger" icon="trash" class="confirm-action" data-confirm="{{ __('previews.confirm_delete_all') }}">
            {{ __('previews.delete_all') }}
          </x-button>
        </form>
      </x-preview-management.card>

      <x-preview-management.card :title="__('previews.maintenance_actions')">
        <form action="{{ route('admin.previews.clean-orphaned') }}" method="POST">
          @csrf
          <x-button type="submit" variant="secondary" icon="broom">
            {{ __('previews.clean_orphaned') }}
          </x-button>
        </form>
      </x-preview-management.card>
    </x-preview-management.section>

    {{-- Individual Actions Section --}}
    <x-preview-management.section :title="__('previews.individual_actions')">
      <x-preview-management.card :title="__('previews.hero_actions')" class="preview-management-card--form">
        <form action="{{ route('admin.previews.individual-hero') }}" method="POST">
          @csrf
          <x-form.select
            name="hero_id"
            :label="__('previews.select_hero')"
            :placeholder="__('previews.select_hero_placeholder')"
            :options="$heroes"
            required
          />
          
          <div class="action-buttons">
            <x-button type="submit" name="action" value="regenerate" variant="info" icon="refresh">
              {{ __('previews.regenerate') }}
            </x-button>
            
            <x-button type="submit" name="action" value="delete" variant="danger" icon="trash" class="confirm-action" data-confirm="{{ __('previews.confirm_delete') }}">
              {{ __('previews.delete') }}
            </x-button>
          </div>
        </form>
      </x-preview-management.card>

      <x-preview-management.card :title="__('previews.card_actions')" class="preview-management-card--form">
        <form action="{{ route('admin.previews.individual-card') }}" method="POST">
          @csrf
          <x-form.select
            name="card_id"
            :label="__('previews.select_card')"
            :placeholder="__('previews.select_card_placeholder')"
            :options="$cards"
            required
          />
          
          <div class="action-buttons">
            <x-button type="submit" name="action" value="regenerate" variant="info" icon="refresh">
              {{ __('previews.regenerate') }}
            </x-button>
            
            <x-button type="submit" name="action" value="delete" variant="danger" icon="trash" class="confirm-action" data-confirm="{{ __('previews.confirm_delete') }}">
              {{ __('previews.delete') }}
            </x-button>
          </div>
        </form>
      </x-preview-management.card>

      <x-preview-management.card :title="__('previews.faction_actions')" class="preview-management-card--form">
        <form action="{{ route('admin.previews.faction-action') }}" method="POST">
          @csrf
          <x-form.select
            name="faction_id"
            :label="__('previews.select_faction')"
            :placeholder="__('previews.select_faction_placeholder')"
            :options="$factions"
            required
          />
          
          <x-form.fieldset :legend="__('previews.select_type')">
            <div class="radio-group">
              <x-form.radio
                name="type"
                id="type_all"
                value="all"
                :label="__('previews.all')"
                checked
              />
              <x-form.radio
                name="type"
                id="type_heroes"
                value="heroes"
                :label="__('previews.only_heroes')"
              />
              <x-form.radio
                name="type"
                id="type_cards"
                value="cards"
                :label="__('previews.only_cards')"
              />
            </div>
          </x-form.fieldset>
          
          <div class="action-buttons">
            <x-button type="submit" name="action" value="regenerate" variant="info" icon="refresh">
              {{ __('previews.regenerate_faction') }}
            </x-button>
            
            <x-button type="submit" name="action" value="delete" variant="danger" icon="trash" class="confirm-action" data-confirm="{{ __('previews.confirm_delete_faction') }}">
              {{ __('previews.delete_faction') }}
            </x-button>
          </div>
        </form>
      </x-preview-management.card>
    </x-preview-management.section>
  </div>
</x-admin-layout>