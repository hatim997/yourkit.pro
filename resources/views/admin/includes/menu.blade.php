<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
            <span class="align-middle">Contruction Tshirt Kit</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Page Management
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-caret-square-down"></i> <span
                        class="align-middle">Category</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.sub-categories.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.sub-categories.index') }}">
                    <i class="far fa-caret-square-down"></i> <span
                        class="align-middle">Sub Category</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.attributes.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.attributes.index') }}">
                    <i class="far fa-plus-square"></i> <span
                        class="align-middle">Attribute</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.products.index') }}">
                    <i class="	fab fa-product-hunt"></i> <span
                        class="align-middle">Kits Products</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.ecommerce.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.ecommerce.index') }}">
                    <i class="	fas fa-gifts"></i> <span
                        class="align-middle">Ecommerce Product</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.products-bundle.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.products-bundle.index') }}">
                    <i class="	fas fa-table"></i> <span
                        class="align-middle">Kits Products Bundle</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.banners.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.banners.index') }}">
                    <i class="	fas fa-sticky-note"></i> <span
                        class="align-middle">Banner</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.faq.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.faq.index') }}">
                    <i class="	fas fa-question"></i> <span
                        class="align-middle">FAQ</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.page.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.page.index') }}">
                    <i class="fab fa-pagelines"></i> <span
                        class="align-middle">Page</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.tax.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.tax.index') }}">
                    <i class="fa fa-percent"></i> <span
                        class="align-middle">Tax</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-cart"></i> <span
                        class="align-middle">Orders</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->routeIs('admin.contact.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.contact.index') }}">
                    <i class="	fas fa-mail-bulk"></i> <span
                        class="align-middle">Contact Messages</span>
                </a>
            </li>
             <li class="sidebar-item {{ request()->routeIs('admin.newslettersubscribers.index') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.newslettersubscribers.index') }}">
                    <i class="	fas fa-mail-bulk"></i> <span
                        class="align-middle">Newsletter Management</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
