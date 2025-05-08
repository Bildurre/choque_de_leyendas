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

        <x-accordion id="admin-sidebar-accordion">
          <!-- Sección Game Components -->
          <x-collapsible-section 
            id="game-components-section" 
            title="{{ __('admin.game') }}"
            :collapsed="!request()->routeIs('admin.factions.*', 'admin.heroes.*', 'admin.cards.*')"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.factions.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('factions.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.heroes.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('heroes.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.cards.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('cards.plural') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>

          <!-- Sección Hero System -->
          <x-collapsible-section 
            id="hero-system-section" 
            title="{{ __('heroes.system') }}"
            :collapsed="!request()->routeIs('admin.hero-races.*', 'admin.hero-superclasses.*', 'admin.hero-classes.*', 'admin.hero-abilities.*', 'admin.hero-attributes-configurations.*')"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.hero-races.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('hero_races.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.hero-superclasses.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('hero_superclasses.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.hero-classes.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.hero-classes.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('hero_classes.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.hero-abilities.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('hero_abilities.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.hero-attributes-configurations.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('hero_attributes.config') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>

          <!-- Sección Card System -->
          <x-collapsible-section 
            id="card-system-section" 
            title="{{ __('cards.system') }}"
            :collapsed="!request()->routeIs('admin.card-types.*', 'admin.equipment-types.*', 'admin.attack-subtypes.*', 'admin.attack-ranges.*')"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.card-types.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('card_types.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.equipment-types.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('equipment_types.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.attack-subtypes.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('attack_subtypes.plural') }}</span>
                </a>
              </li>
              <li class="admin-sidebar__item">
                <a href="#" class="admin-sidebar__link {{ request()->routeIs('admin.attack-ranges.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('attack_ranges.plural') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>

          <!-- Sección Content -->
          <x-collapsible-section 
            id="content-section" 
            title="{{ __('admin.content') }}"
            :collapsed="!request()->routeIs('admin.pages.*')"
          >
            <ul class="admin-sidebar__submenu">
              <li class="admin-sidebar__item">
                <a href="{{ route('admin.pages.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.pages.*') ? 'admin-sidebar__link--active' : '' }}">
                  <span class="admin-sidebar__text">{{ __('pages.plural') }}</span>
                </a>
              </li>
            </ul>
          </x-collapsible-section>
        </x-accordion>
      </ul>
    </nav>
  </div>
</aside>