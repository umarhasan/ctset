<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    <div class="sidebar-brand">
        <a href="
            @role('Admin') {{ route('admin.dashboard') }}
            @elserole('Assessor') {{ route('assessor.dashboard') }}
            @else {{ route('trainee.dashboard') }}
            @endrole
        " class="brand-link">
            <img src="{{ asset('adminlte/assets/img/logo.png') }}"
                 class="brand-image opacity-75 shadow">

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

                {{-- Exam Menu - Permission based --}}
                @can('exams.index')
                <li class="nav-item {{ request()->routeIs('exams.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('exams.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-journal-text"></i>
                        <p>
                            Exam
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('exams.index') }}"
                            class="nav-link {{ request()->routeIs('exams.index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>View Exam</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
                {{-- My Trainee --}}
                {{-- Exam Menu - Permission based --}}
                @can('trainee.invitations')
                    <li class="nav-item {{ request()->routeIs('trainee.invitations.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('trainee.invitations.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-text"></i>
                            <p>
                                My invitations
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('trainee.invitations') }}"
                                class="nav-link {{ request()->routeIs('exams.index') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Trainee Invitations</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                {{-- Exam Invite Dropdown - Permission based --}}
                @php
                    // Check if user has any exam invite permission
                    $hasExamInvitePermission =
                        auth()->user()->can('view-pending-exams') ||
                        auth()->user()->can('send-invites') ||
                        auth()->user()->can('view-sent-invites');
                @endphp

                @if($hasExamInvitePermission)
                    <li class="nav-item {{ request()->routeIs('exams.pending', 'exams.send-invite', 'exams.sent-invites', 'exams.view-invited-students') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('exams.pending', 'exams.send-invite', 'exams.sent-invites', 'exams.view-invited-students') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-envelope"></i>
                            <p>
                                Exam Invite
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            {{-- Invite Trainee - Specific permission required --}}
                            @can('view-pending-exams')
                            <li class="nav-item">
                                <a href="{{ route('exams.pending') }}"
                                class="nav-link {{ request()->routeIs('exams.pending') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Invite Trainee</p>
                                </a>
                            </li>
                            @endcan

                            {{-- View Invite - Specific permission required --}}
                            @can('view-sent-invites')
                            <li class="nav-item">
                                <a href="{{ route('exams.sent-invites') }}"
                                class="nav-link {{ request()->routeIs('exams.sent-invites') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>View Invite</p>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{-- Master Menu - Only for Admin Role --}}
                @role('Admin')
                <li class="nav-item {{ request()->routeIs(
                        'test-types.*',
                        'marketing-types.*',
                        'question-types.*',
                        'time-table-categories.*',
                        'assignment-from-types.*',
                        'video-main-categories.*',
                        'video-categories.*',
                        'exam-duration-types.*',
                        'yes-no-options.*'
                    ) ? 'menu-open' : '' }}">

                    <a href="#" class="nav-link {{ request()->routeIs(
                            'test-types.*',
                            'marketing-types.*',
                            'question-types.*',
                            'time-table-categories.*',
                            'assignment-from-types.*',
                            'video-main-categories.*',
                            'video-categories.*',
                            'exam-duration-types.*',
                            'yes-no-options.*'
                        ) ? 'active' : '' }}">
                        <i class="nav-icon bi bi-gear-fill"></i>
                        <p>
                            Master
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('test-types.index') }}"
                            class="nav-link {{ request()->routeIs('test-types.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Test Types</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('marketing-types.index') }}"
                            class="nav-link {{ request()->routeIs('marketing-types.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Marketing Types</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('question-types.index') }}"
                            class="nav-link {{ request()->routeIs('question-types.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Question Types</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('time-table-categories.index') }}"
                            class="nav-link {{ request()->routeIs('time-table-categories.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Time Table Categories</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('assignment-from-types.index') }}"
                            class="nav-link {{ request()->routeIs('assignment-from-types.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Assignment From Types</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('video-main-categories.index') }}"
                            class="nav-link {{ request()->routeIs('video-main-categories.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Video Main Categories</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('video-categories.index') }}"
                            class="nav-link {{ request()->routeIs('video-categories.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Video Categories</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('exam-duration-types.index') }}"
                            class="nav-link {{ request()->routeIs('exam-duration-types.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Exam Duration Types</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('yes-no-options.index') }}"
                            class="nav-link {{ request()->routeIs('yes-no-options.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Yes / No Options</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Results Menu --}}
                @php
                    $hasResultPermission =
                        auth()->user()->can('results.pending') ||
                        auth()->user()->can('results.view');
                @endphp

                @if($hasResultPermission)
                <li class="nav-item {{ request()->routeIs('results.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('results.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bar-chart-fill"></i>
                        <p>
                            Results
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        {{-- Pending Results --}}
                        @can('results.pending')
                        <li class="nav-item">
                            <a href="{{ route('results.pending') }}"
                            class="nav-link {{ request()->routeIs('results.pending') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Pending Results</p>
                            </a>
                        </li>
                        @endcan


                    </ul>
                </li>
                @endif
                @endrole
                @can('exam_matrices.index')
                    <li class="nav-item {{ request()->routeIs('exam_matrices.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('exam_matrices.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-grid-fill"></i>
                            <p>
                                    Matrix
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('exam_matrices.index') }}" class="nav-link {{ request()->routeIs('exam_matrices.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>All Matrix</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Work Cloud --}}
                @can('work_clouds.index')
                    <li class="nav-item {{ request()->routeIs('work_clouds.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('work_clouds.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-cloud-fill"></i>
                            <p>
                                Work Cloud
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('work_clouds.index') }}" class="nav-link {{ request()->routeIs('work_clouds.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Manage Work Cloud</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Hospital --}}
                @can('hospitals.index')
                    <li class="nav-item {{ request()->routeIs('hospitals.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('hospitals.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-hospital-fill"></i>
                            <p>
                                Hospital
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('hospitals.index') }}" class="nav-link {{ request()->routeIs('hospitals.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Manage Hospitals</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                @role('Trainee')
                <li class="nav-item">
                    <a href="{{ route('trainee.exams') }}"
                    class="nav-link {{ request()->routeIs('trainee.results.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clipboard-check"></i>
                        <p>Exams</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainee.results.index') }}"
                    class="nav-link {{ request()->routeIs('trainee.results.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clipboard-check"></i>
                        <p>My Results</p>
                    </a>
                </li>
                @endrole

                {{-- Book And Assignments --}}
                @canany(['subjects.index', 'topics.index']) {{-- Check if user has at least one permission --}}
                    <li class="nav-item {{ request()->routeIs('subjects.*', 'topics.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('subjects.*', 'topics.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-book-fill"></i>
                            <p>
                                Book & Assignments
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            @can('subjects.index') {{-- Permission for Subjects --}}
                                <li class="nav-item">
                                    <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>View Subjects</p>
                                    </a>
                                </li>
                            @endcan

                            @can('topics.index') {{-- Permission for Topics --}}
                                <li class="nav-item">
                                    <a href="{{ route('topics.index') }}" class="nav-link {{ request()->routeIs('topics.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>View Topics</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany



                {{-- User Management - Permission based --}}
                @php
                    $hasUserManagement =
                        auth()->user()->can('users.index') ||
                        auth()->user()->can('roles.index') ||
                        auth()->user()->can('permissions.index')||
                        auth()->user()->can('semesters.index');
                @endphp

                @if($hasUserManagement)
                    <li class="nav-item {{ request()->routeIs('users.*','roles.*','permissions.*','semesters.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('users.*','roles.*','permissions.*','semesters.*') ? 'active' : '' }}">
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

                            @can('semesters.index')
                            <li class="nav-item">
                                    <a href="{{ route('semesters.index') }}" class="nav-link {{ request()->routeIs('semesters.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>Semesters Setup</p>
                                    </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{-- Profile - Permission based --}}
                @can('profile.view')
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}"
                       class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person"></i>
                        <p>Profile</p>
                    </a>
                </li>
                @endcan

                {{-- Logout - Always visible for logged in users --}}
                <li class="nav-item mt-3">
                    <a href="#"
                       class="nav-link text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Logout</p>
                    </a>

                    <form id="logout-form"
                          action="{{ route('logout') }}"
                          method="POST"
                          class="d-none">
                        @csrf
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>
