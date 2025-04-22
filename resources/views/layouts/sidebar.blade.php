<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset(\App\Helpers\Helper::getFavicon()) }}" alt="{{ env('APP_NAME') }}">
            </span>
            <span class="app-brand-text demo menu-text fw-bold"
                style="width: 150px; font-size: 15px;">{{ \App\Helpers\Helper::getCompanyName() }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{ __('Dashboard') }}</div>
            </a>
        </li>

        <!-- Apps & Pages -->
        <li class="menu-header small">
            <span class="menu-header-text">{{ __('Apps & Pages') }}</span>
        </li>

        @can(['view category'])
            <li class="menu-item {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.categories.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-layout-grid"></i>
                    <div>{{ __('Category') }}</div>
                </a>
            </li>
        @endcan
        @can(['view sub category'])
            <li class="menu-item {{ request()->routeIs('dashboard.sub-categories.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.sub-categories.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-list-tree"></i>
                    <div>{{ __('Sub Category') }}</div>
                </a>
            </li>
        @endcan
        @can(['view attribute'])
            <li class="menu-item {{ request()->routeIs('dashboard.attributes.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.attributes.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-color-swatch"></i>
                    <div>{{ __('Attribute') }}</div>
                </a>
            </li>
        @endcan
        @can(['view kit product'])
            <li class="menu-item {{ request()->routeIs('dashboard.kit-products.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.kit-products.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-package"></i>
                    <div>{{ __('Kits Products') }}</div>
                </a>
            </li>
        @endcan
        @can(['view ecommerce product'])
            <li class="menu-item {{ request()->routeIs('dashboard.ecommerce-products.*') || request()->routeIs('dashboard.ecommerce-product-attributes.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.ecommerce-products.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
                    <div>{{ __('Ecommerce Products') }}</div>
                </a>
            </li>
        @endcan
        @can(['view kit product bundle'])
            <li class="menu-item {{ request()->routeIs('dashboard.kit-product-bundles.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.kit-product-bundles.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-packages"></i>
                    <div>{{ __('Kits Products Bundle') }}</div>
                </a>
            </li>
        @endcan
        @can(['view promo code'])
            <li class="menu-item {{ request()->routeIs('dashboard.promo-codes.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.promo-codes.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-ticket"></i>
                    <div>{{ __('Promo Codes') }}</div>
                </a>
            </li>
        @endcan
        @can(['view banner'])
            <li class="menu-item {{ request()->routeIs('dashboard.banners.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.banners.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-photo"></i>
                    <div>{{ __('Banners') }}</div>
                </a>
            </li>
        @endcan
        @can(['view faq'])
            <li class="menu-item {{ request()->routeIs('dashboard.faqs.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.faqs.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-help-circle"></i>
                    <div>{{ __('FAQ') }}</div>
                </a>
            </li>
        @endcan
        @can(['view page'])
            <li class="menu-item {{ request()->routeIs('dashboard.pages.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.pages.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-book"></i>
                    <div>{{ __('Pages') }}</div>
                </a>
            </li>
        @endcan
        @can(['view tax'])
            <li class="menu-item {{ request()->routeIs('dashboard.taxes.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.taxes.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-currency-dollar"></i>
                    <div>{{ __('Tax') }}</div>
                </a>
            </li>
        @endcan
        @can(['view order'])
            <li class="menu-item {{ request()->routeIs('dashboard.orders.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.orders.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-truck"></i>
                    <div>{{ __('Orders') }}</div>
                </a>
            </li>
        @endcan
        @can(['view contact message'])
            <li class="menu-item {{ request()->routeIs('dashboard.contact-messages.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.contact-messages.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-message"></i>
                    <div>{{ __('Contact Messages') }}</div>
                </a>
            </li>
        @endcan
        @can(['view newsletter'])
            <li class="menu-item {{ request()->routeIs('dashboard.newsletters.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.newsletters.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-mail"></i>
                    <div>{{ __('Newsletter Subscribers') }}</div>
                </a>
            </li>
        @endcan

        @canany(['view user', 'view archived user'])
            <li
                class="menu-item {{ request()->routeIs('dashboard.user.*') || request()->routeIs('dashboard.archived-user.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>{{ __('Users') }}</div>
                </a>
                <ul class="menu-sub">
                    @can(['view user'])
                        <li class="menu-item {{ request()->routeIs('dashboard.user.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.user.index') }}" class="menu-link">
                                <div>{{ __('All Users') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can(['view archived user'])
                        <li class="menu-item {{ request()->routeIs('dashboard.archived-user.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.archived-user.index') }}" class="menu-link">
                                <div>{{ __('Archived Users') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @canany(['view role', 'view permission'])
            <li
                class="menu-item {{ request()->routeIs('dashboard.roles.*') || request()->routeIs('dashboard.permissions.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    {{-- <i class="menu-icon tf-icons ti ti-settings"></i> --}}
                    <i class="menu-icon tf-icons ti ti-shield-lock"></i>
                    <div>{{ __('Roles & Permissions') }}</div>
                </a>
                <ul class="menu-sub">
                    @can(['view role'])
                        <li class="menu-item {{ request()->routeIs('dashboard.roles.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.roles.index') }}" class="menu-link">
                                <div>{{ __('Roles') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can(['view permission'])
                        <li class="menu-item {{ request()->routeIs('dashboard.permissions.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.permissions.index') }}" class="menu-link">
                                <div>{{ __('Permissions') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can(['view setting'])
            <li class="menu-item {{ request()->routeIs('dashboard.setting.*') ? 'active' : '' }}">
                <a href="{{ route('dashboard.setting.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-settings"></i>
                    <div>{{ __('Settings') }}</div>
                </a>
            </li>
        @endcan
    </ul>
</aside>
