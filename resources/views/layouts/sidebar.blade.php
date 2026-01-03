<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <a href="
            @role('Admin') {{ route('admin.dashboard') }}
            @elserole('Assessor') {{ route('assessor.dashboard') }}
            @else {{ route('trainee.dashboard') }}
            @endrole
        " class="brand-link">
            <img src="{{ asset('adminlte/assets/img/AdminLTELogo.png') }}"
                 class="brand-image opacity-75 shadow">
            <span class="brand-text fw-light">{{ config('app.name') }}</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview">

                <li class="nav-item">
                    <a href="
                        @role('Admin') {{ route('admin.dashboard') }}
                        @elserole('Assessor') {{ route('assessor.dashboard') }}
                        @else {{ route('trainee.dashboard') }}
                        @endrole
                    "
                       class="nav-link {{ request()->routeIs('*dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                 {{-- User Management --}}
                @php
                    $hasUserManagement = auth()->user()->can('users.index') 
                                        || auth()->user()->can('roles.index') 
                                        || auth()->user()->can('permissions.index');
                @endphp
                @if($hasUserManagement)
                <li class="nav-item {{ request()->routeIs('users.*','roles.*','permissions.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('users.*','roles.*','permissions.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>
                            User Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @can('users.index')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        @endcan

                        @can('roles.index')
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}"
                               class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endcan

                        @can('permissions.index')
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}"
                               class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif

                {{-- Profile --}}
                @can('profile.view')
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}"
                       class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person"></i>
                        <p>Profile</p>
                    </a>
                </li>
                @endcan

                {{-- Example: Additional dynamic menu from permissions --}}
                @php
                    $allPermissions = auth()->user()->getAllPermissions()->pluck('name');
                @endphp

                @foreach($allPermissions as $perm)
                    @if(str_contains($perm, 'custommenu.')) {{-- e.g. custommenu.reports --}}
                        <li class="nav-item">
                            <a href="{{ route($perm) }}" class="nav-link">
                                <i class="nav-icon bi bi-file-text"></i>
                                <p>{{ ucfirst(str_replace('custommenu.', '', $perm)) }}</p>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
