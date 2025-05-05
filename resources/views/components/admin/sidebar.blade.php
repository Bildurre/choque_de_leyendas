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

        

        <!-- Sección Game Components -->
        <li class="admin-sidebar__section">
          <button class="admin-sidebar__section-toggle" data-section="game-components">
            <span class="admin-sidebar__section-title">{{ __('admin.game') }}</span>
            <x-icon name="chevron-down" class="admin-sidebar__section-icon" />
          </button>
          
          <ul class="admin-sidebar__submenu" id="section-game-components">
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
        </li>

        <!-- Sección Hero System -->
        <li class="admin-sidebar__section">
          <button class="admin-sidebar__section-toggle" data-section="hero-system">
            <span class="admin-sidebar__section-title">{{ __('heroes.system') }}</span>
            <x-icon name="chevron-down" class="admin-sidebar__section-icon" />
          </button>
          
          <ul class="admin-sidebar__submenu" id="section-hero-system">
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
        </li>

        <!-- Sección Card System -->
        <li class="admin-sidebar__section">
          <button class="admin-sidebar__section-toggle" data-section="card-system">
            <span class="admin-sidebar__section-title">{{ __('cards.system') }}</span>
            <x-icon name="chevron-down" class="admin-sidebar__section-icon" />
          </button>
          
          <ul class="admin-sidebar__submenu" id="section-card-system">
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
        </li>

        <!-- Sección Content -->
        <li class="admin-sidebar__section">
          <button class="admin-sidebar__section-toggle" data-section="content">
            <span class="admin-sidebar__section-title">{{ __('admin.content') }}</span>
            <x-icon name="chevron-down" class="admin-sidebar__section-icon" />
          </button>
          
          <ul class="admin-sidebar__submenu" id="section-content">
            <li class="admin-sidebar__item">
              <a href="{{ route('admin.content.pages.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.content.pages.*') ? 'admin-sidebar__link--active' : '' }}">
                <span class="admin-sidebar__text">{{ __('pages.plural') }}</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>