<aside class="admin-sidebar" id="admin-sidebar">
  <div class="admin-sidebar__inner">
    <nav class="admin-sidebar__nav">
      <ul class="admin-sidebar__menu">
        <!-- Enlaces directos -->
        <li class="admin-sidebar__item">
          <a href="{{ route('welcome') }}" class="admin-sidebar__link">
            <x-icon name="home" />
            <span class="admin-sidebar__text">{{ __('admin.home') }}</span>
          </a>
        </li>
        
        <li class="admin-sidebar__item">
          <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__link {{ request()->routeIs('admin.dashboard') ? 'admin-sidebar__link--active' : '' }}">
            <x-icon name="dashboard" />
            <span class="admin-sidebar__text">{{ __('admin.dashboard') }}</span>
          </a>
        </li>

        <x-accordion id="admin-sidebar-accordion" :is-sidebar="true">
          <!-- Sección Game Components -->
          <x-collapsible-section 
            id="game-components-section" 
            title="{{ __('admin.game') }}"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.factions.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.factions.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('factions.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.heroes.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.heroes.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('heroes.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.cards.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.cards.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('cards.plural') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>

          <!-- Sección Hero System -->
          <x-collapsible-section 
            id="hero-system-section" 
            title="{{ __('heroes.system') }}"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.hero-races.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.hero-races.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('hero_races.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.hero-superclasses.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.hero-superclasses.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('hero_superclasses.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.hero-classes.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.hero-classes.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('hero_classes.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.hero-abilities.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.hero-abilities.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('hero_abilities.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.hero-attributes-configurations.edit') }}" class="admin-sidebar__link {{ request()->routeIs('admin.hero-attributes-configurations.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('hero_attributes.config') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>

          <!-- Sección Game Components -->
          <x-collapsible-section
            title="{{ __('admin.game') }}"
            id="game-section" 
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.faction-decks.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.faction-decks.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('faction_deck.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.game-modes.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.game-modes.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('game-modes.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.deck-attributes-configurations.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.deck-attributes-configurations.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('deck-attributes-configurations.plural') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>

          <!-- Sección Card System -->
          <x-collapsible-section 
            id="card-system-section" 
            title="{{ __('cards.system') }}"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.card-types.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.card-types.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('card_types.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.equipment-types.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.equipment-types.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('equipment_types.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.attack-subtypes.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.attack-subtypes.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('attack_subtypes.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.attack-ranges.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.attack-ranges.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('attack_ranges.plural') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>

          <!-- Sección Content -->
          <x-collapsible-section 
            id="content-section" 
            title="{{ __('admin.content') }}"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.pages.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.pages.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('pages.plural') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>
          
          <!-- Sección Statistics & Analysis -->
          <x-collapsible-section 
            id="statistics-section" 
            title="{{ __('admin.statistics') }}"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.dashboard') }}#factions-stats" class="admin-sidebar__link">
                  <span class="admin-sidebar__text">{{ __('statistics.factions') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.dashboard') }}#cards-stats" class="admin-sidebar__link">
                  <span class="admin-sidebar__text">{{ __('statistics.cards') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.dashboard') }}#balance-analysis" class="admin-sidebar__link">
                  <span class="admin-sidebar__text">{{ __('statistics.balance_analysis') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>
          
          <!-- Sección Export -->
          <x-collapsible-section 
            id="export-section" 
            title="{{ __('admin.export') }}"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.dashboard') }}#export-cards" class="admin-sidebar__link">
                  <span class="admin-sidebar__text">{{ __('export.cards') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.dashboard') }}#export-heroes" class="admin-sidebar__link">
                  <span class="admin-sidebar__text">{{ __('export.heroes') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.dashboard') }}#export-rules" class="admin-sidebar__link">
                  <span class="admin-sidebar__text">{{ __('export.rules') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>
          
          <!-- Sección Admin Users -->
          <x-collapsible-section 
            id="users-section" 
            title="{{ __('admin.users') }}"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.dashboard') }}#admin-users" class="admin-sidebar__link">
                  <span class="admin-sidebar__text">{{ __('users.admins') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>
        </x-accordion>
      </ul>
    </nav>
  </div>
</aside>