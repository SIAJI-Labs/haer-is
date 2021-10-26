<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('system.index') }}" class="brand-link d-flex justify-content-center">
        <span class="brand-text font-weight-light">{{ $wtitle ?? env('APP_NAME') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <img src="{{ getAvatar(\Auth::user()->name) }}" class="img-circle elevation-2" alt="User Avatar">
            </div>
            <div class="info">
                <a href="#" class="{{ !empty($sidebar_menu) ? ($sidebar_menu == 'profile' ? 'active' : '') : '' }} d-block">
                    {{ Auth::user()->name }}
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="sidebar-menu nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('system.index') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'dashboard' ? 'active' : '') : '' }}">
                        <i class="nav-icon fas fa-tv"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-header">MISCELLANEOUS</li>
                <li class="nav-item">
                    <a href="{{ route('system.profile.index') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'profile' ? 'active' : '') : '' }}">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                {{-- @can('website_configuration-list')
                    <li class="nav-item">
                        <a href="{{ route('adm.website-configuration.index') }}" class="nav-link d-flex align-items-center {{ !empty($sidebar_menu) ? ($sidebar_menu == 'website-configuration' ? 'active' : '') : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Pengaturan Website
                            </p>
                        </a>
                    </li>
                @endcan --}}
                {{-- @can('documentation-list')
                    <li class="nav-item">
                        <a href="{{ route('larecipe.index') }}" class="nav-link d-flex align-items-center" target="_blank">
                            <i class="nav-icon far fa-circle text-info"></i>
                            <p>Dokumentasi</p>
                        </a>
                    </li>
                @endcan --}}
                <li class="nav-item">
                    <a href="{{ route('log-viewer::dashboard') }}" class="nav-link d-flex align-items-center" target="_blank">
                        <i class="nav-icon far fa-circle text-info"></i>
                        <p>Sistem Log</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link d-flex align-items-center" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p>Log Out</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
