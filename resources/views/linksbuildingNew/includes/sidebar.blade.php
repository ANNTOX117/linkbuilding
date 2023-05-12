
<nav class="sidebar nobackground sidebar-offcanvas" id="">
    <ul class="nav">
        <li class="nav-item nav-profile">
            @livewire('sidebar')
        </li>
        @if(permission('dashboard', 'read'))
        <li class="nav-item pt-2 @if(this_route() == 'dashboard') active @endif">
            <a class="nav-link" href="{{ route('dashboard') }}">
                @if(this_route() == 'dashboard') <span class="before"></span> @endif
                <i class="fas fa-tachometer-alt mr-2"></i>
                <span class="menu-title">{{__('Dashboard')}}</span>
            </a>
        </li>
        @endif
        @if(permission('languages', 'read'))
        <li class="nav-item @if(this_route() == 'languages') active @endif">
            <a class="nav-link" href="{{ route('languages') }}">
                @if(this_route() == 'languages') <span class="before"></span> @endif
                <i class="fas fa-language mr-2"></i>
                <span class="menu-title">{{__('Languages')}}</span>
            </a>
        </li>
        @endif
        @if(permission('templates', 'read'))
            <li class="nav-item @if(this_route() == 'templates') active @endif">
                <a class="nav-link" href="{{ route('templates') }}">
                    @if(this_route() == 'templates') <span class="before"></span> @endif
                        <i class="fas fa-columns mr-2"></i>
                    <span class="menu-title">{{__('Templates')}}</span>
                </a>
            </li>
        @endif
        @if(permission('banners', 'read'))
            <li class="nav-item @if(this_route() == 'banners') active @endif">
                <a class="nav-link" href="{{ route('banners') }}">
                    @if(this_route() == 'banners') <span class="before"></span> @endif
                        <i class="fas fa-columns mr-2"></i>
                    <span class="menu-title">{{__('Banners')}}</span>
                </a>
            </li>
        @endif
        @if(permission('categories', 'read'))
        <li class="nav-item @if(this_route() == 'categories') active @endif">
            <a class="nav-link" href="{{ route('categories') }}">
                @if(this_route() == 'categories') <span class="before"></span> @endif
                <i class="fas fa-list mr-2"></i>
                <span class="menu-title">{{__('Categories')}}</span>
            </a>
        </li>
        @endif
        @if(permission('sites', 'read'))
        <li class="nav-item @if(this_route() == 'sites') active @endif">
            <a class="nav-link" href="{{ route('sites') }}">
                @if(this_route() == 'sites') <span class="before"></span> @endif
                <i class="fas fa-globe mr-2"></i>
                <span class="menu-title">{{__('Sites')}}</span>
            </a>
        </li>
        @endif
        @if(permission('wordpress', 'read'))
            <li class="nav-item @if(this_route() == 'wordpress') active @endif">
                <a class="nav-link" href="{{ route('wordpress') }}">
                    @if(this_route() == 'wordpress') <span class="before"></span> @endif
                    <i class="fab fa-wordpress mr-2"></i>
                    <span class="menu-title">{{__('Wordpress')}}</span>
                </a>
            </li>
        @endif
        @if(permission('authorities', 'read'))
        <li class="nav-item @if(this_route() == 'authority') activeA @endif">
            <a class="nav-link" href="{{ route('authority') }}">
                @if(this_route() == 'authority') <span class="before"></span> @endif
                <i class="fas fa-sitemap mr-2"></i>
                <span class="menu-title">{{__('Authority sites')}}</span>
            </a>
        </li>
        @endif
        @if(permission('articles', 'read'))
        <li class="nav-item @if(this_route() == 'articles') active @endif">
            <a class="nav-link" href="{{ route('articles') }}">
                @if(this_route() == 'articles') <span class="before"></span> @endif
                <i class="fas fa-feather-alt mr-2"></i>
                <span class="menu-title">{{__('Articles')}}</span>
            </a>
        </li>
        @endif
        @if(permission('packages', 'read'))
        <li class="nav-item @if(this_route() == 'packages') active @endif">
            <a class="nav-link" href="{{ route('packages') }}">
                @if(this_route() == 'packages') <span class="before"></span> @endif
                <i class="fas fa-box mr-2"></i>
                <span class="menu-title">{{__('Packages')}}</span>
            </a>
        </li>
        @endif
        @if(permission('links', 'read'))
        <li class="nav-item @if(this_route() == 'links') active @endif">
            <a class="nav-link" href="{{ route('links') }}">
                @if(this_route() == 'links') <span class="before"></span> @endif
                <i class="fas fa-link mr-2"></i>
                <span class="menu-title">{{__('Links')}}</span>
            </a>
        </li>
        @endif
        @if(permission('approvals', 'read'))
        <li class="nav-item @if(this_route() == 'approvements') active @endif">
            <a class="nav-link" href="{{ route('approvements') }}">
                @if(this_route() == 'approvements') <span class="before"></span> @endif
                <i class="fas fa-check mr-2"></i>
                <span class="menu-title">{{__('Approvals')}}</span>
            </a>
        </li>
        @endif
        @if(permission('profiles', 'read'))
            <li class="nav-item @if(this_route() == 'profiles') active @endif">
                <a class="nav-link" href="{{ route('profiles') }}">
                    @if(this_route() == 'users') <span class="before"></span> @endif
                    <i class="fas fa-users mr-2"></i>
                    <span class="menu-title">{{__('Profiles')}}</span>
                </a>
            </li>
        @endif
        @if(permission('pages', 'read'))
            <li class="nav-item @if(this_route() === 'pages') active @endif">
                <a class="nav-link" href="{{ route('pages') }}">
                    @if(this_route() === 'pages') <span class="before"></span> @endif
                    <i class="fas fa-file mr-2"></i>
                    <span class="menu-title">{{__('Pages')}}</span>
                </a>
            </li>
        @endif
        @if(permission('seopages', 'read'))
            <li class="nav-item @if(this_route() === 'seo-pages') active @endif">
                <a class="nav-link" href="{{ route('seo-pages') }}">
                    @if(this_route() === 'seo-pages') <span class="before"></span> @endif
                    <i class="fas fa-columns mr-2"></i>
                    <span class="menu-title">{{__('Seo pages')}}</span>
                </a>
            </li>
        @endif
        @if(permission('users', 'read'))
        <li class="nav-item @if(this_route() == 'users') active @endif">
            <a class="nav-link" href="{{ route('users') }}">
                @if(this_route() == 'users') <span class="before"></span> @endif
                <i class="fas fa-users mr-2"></i>
                <span class="menu-title">{{__('Users')}}</span>
            </a>
        </li>
        @endif
        @if(permission('mailing', 'read'))
        <li class="nav-item @if(this_route() == 'emails') active @endif">
            <a class="nav-link" href="{{ route('emails') }}">
                @if(this_route() == 'emails') <span class="before"></span> @endif
                <i class="fas fa-envelope mr-2"></i>
                <span class="menu-title">{{__('Mailing')}}</span>
            </a>
        </li>
        @endif
        @if(permission('taxes', 'read'))
        <li class="nav-item @if(this_route() == 'taxes') active @endif">
            <a class="nav-link" href="{{ route('taxes') }}">
                @if(this_route() == 'taxes') <span class="before"></span> @endif
                <i class="fas fa-file-invoice-dollar mr-2"></i>
                <span class="menu-title">{{__('Taxes')}}</span>
            </a>
        </li>
        @endif
        @if(permission('texts', 'read'))
        <li class="nav-item @if(this_route() == 'texts') active @endif">
            <a class="nav-link" href="{{ route('texts') }}">
                @if(this_route() == 'texts') <span class="before"></span> @endif
                <i class="fas fa-file-alt mr-2"></i>
                <span class="menu-title">{{__('Static texts')}}</span>
            </a>
        </li>
        @endif
        @if(permission('pages', 'read'))
            <li class="nav-item @if(this_route() == 'metas') active @endif">
                <a class="nav-link" href="{{ route('metas') }}">
                    @if(this_route() == 'metas') <span class="before"></span> @endif
                    <i class="fas fa-file mr-2"></i>
                    <span class="menu-title">{{__('Metatags')}}</span>
                </a>
            </li>
        @endif
        @if(permission('payments', 'read'))
        <li class="nav-item @if(this_route() == 'payments') active @endif">
            <a class="nav-link" href="{{ route('payments') }}">
                @if(this_route() == 'payments') <span class="before"></span> @endif
                <i class="fas fa-wallet mr-2"></i>
                <span class="menu-title">{{__('Payments')}}</span>
            </a>
        </li>
        @endif
        @if(permission('discounts', 'read'))
        <li class="nav-item @if(this_route() == 'discounts') active @endif">
            <a class="nav-link" href="{{ route('discounts') }}">
                @if(this_route() == 'discounts') <span class="before"></span> @endif
                <i class="fas fa-tag mr-2"></i>
                <span class="menu-title">{{__('Discounts')}}</span>
            </a>
        </li>
        @endif
        @if(permission('general', 'read'))
        <li class="nav-item @if(this_route() == 'general') active @endif">
            <a class="nav-link" href="{{ route('general') }}">
                @if(this_route() == 'general') <span class="before"></span> @endif
                <i class="fas fa-cog mr-2"></i>
                <span class="menu-title">{{__('General')}}</span>
            </a>
        </li>
        @endif
        @if(permission('general', 'read'))
        <li class="nav-item @if(this_route() == 'content') active @endif">
            <a class="nav-link" href="{{ route('content') }}">
                @if(this_route() == 'content') <span class="before"></span> @endif
                <i class="fa fa-box mr-2"></i>
                <span class="menu-title">{{__('Page Builder')}}</span>
            </a>
        </li>
        @endif

        @if(permission('general', 'read'))
            <li class="nav-item @if(this_route() == 'validate') active @endif">
                <a class="nav-link" href="{{ route('validate') }}">
                    @if(this_route() == 'validate') <span class="before"></span> @endif
                    <i class="fas fa-cog mr-2"></i>
                    <span class="menu-title">{{__('Validate')}}</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
