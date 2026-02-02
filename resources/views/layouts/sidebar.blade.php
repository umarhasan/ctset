<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="@role('Admin'){{ route('admin.dashboard') }}@elserole('Assessor'){{ route('assessor.dashboard') }}@else{{ route('trainee.dashboard') }}@endrole" class="brand-link">
            <img src="{{ asset('adminlte/assets/img/logo.png') }}" class="brand-image opacity-75 shadow">
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="@role('Admin'){{ route('admin.dashboard') }}@elserole('Assessor'){{ route('assessor.dashboard') }}@else{{ route('trainee.dashboard') }}@endrole"
                       class="nav-link {{ request()->routeIs('*dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Profiles --}}
                @unlessrole('Admin')
                <li class="nav-item {{ request()->routeIs('profile.*','public.profile.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('profile.*','public.profile.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>Profiles<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('my.profile') }}"
                               class="nav-link {{ request()->routeIs('my.profile') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-person"></i>
                                <p>My Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('public.profile', auth()->id()) }}"
                               class="nav-link {{ request()->routeIs('public.profile.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-globe"></i>
                                <p>Public Profile</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endunlessrole

                {{-- Exam --}}
                @can('exams.index')
                <li class="nav-item {{ request()->routeIs('exams.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('exams.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-journal-text"></i>
                        <p>Exam<i class="nav-arrow bi bi-chevron-right"></i></p>
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

                {{-- Exam Invite --}}
                @php
                    $hasExamInvite = auth()->user()->can('view-pending-exams') || auth()->user()->can('view-sent-invites');
                @endphp

                @if($hasExamInvite)
                <li class="nav-item {{ request()->routeIs('exams.pending', 'exams.send-invite', 'exams.sent-invites', 'exams.view-invited-students') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('exams.pending', 'exams.send-invite', 'exams.sent-invites', 'exams.view-invited-students') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-envelope"></i>
                        <p>Exam Invite<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('view-pending-exams')
                        <li class="nav-item">
                            <a href="{{ route('exams.pending') }}"
                               class="nav-link {{ request()->routeIs('exams.pending') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Invite Trainee</p>
                            </a>
                        </li>
                        @endcan
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

                {{-- Master --}}
                @role('Admin')
                @php
                    $masterRoutes = [
                        'test-types.*', 'marketing-types.*', 'question-types.*',
                        'time-table-categories.*', 'assignment-from-types.*',
                        'video-main-categories.*', 'video-categories.*',
                        'exam-duration-types.*', 'yes-no-options.*', 'rotations.*'
                    ];

                    $masterMenu = [
                        'Test Types' => 'test-types.index',
                        'Marketing Types' => 'marketing-types.index',
                        'Question Types' => 'question-types.index',
                        'Time Table Categories' => 'time-table-categories.index',
                        'Assignment From Types' => 'assignment-from-types.index',
                        'Video Main Categories' => 'video-main-categories.index',
                        'Video Categories' => 'video-categories.index',
                        'Exam Duration Types' => 'exam-duration-types.index',
                        'Yes / No Options' => 'yes-no-options.index',
                        'Rotations' => 'rotations.index'
                    ];
                @endphp

                <li class="nav-item {{ request()->routeIs(implode(',', $masterRoutes)) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs(implode(',', $masterRoutes)) ? 'active' : '' }}">
                        <i class="nav-icon bi bi-gear-fill"></i>
                        <p>Master<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach($masterMenu as $label => $route)
                        <li class="nav-item">
                            <a href="{{ route($route) }}"
                               class="nav-link {{ request()->routeIs(str_replace('.index', '.*', $route)) ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>{{ $label }}</p>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endrole

                {{-- Matrix --}}
                @can('exam_matrices.index')
                <li class="nav-item {{ request()->routeIs('exam_matrices.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('exam_matrices.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-grid-fill"></i>
                        <p>Matrix<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('exam_matrices.index') }}"
                               class="nav-link {{ request()->routeIs('exam_matrices.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>All Matrix</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Results --}}
                @php $hasResult = auth()->user()->can('results.pending') || auth()->user()->can('results.view'); @endphp
                @if($hasResult)
                <li class="nav-item {{ request()->routeIs('results.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('results.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bar-chart-fill"></i>
                        <p>Results<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
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

                {{-- Work Cloud --}}
                @can('work_clouds.index')
                <li class="nav-item {{ request()->routeIs('work_clouds.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('work_clouds.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-cloud-fill"></i>
                        <p>Work Cloud<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('work_clouds.index') }}"
                               class="nav-link {{ request()->routeIs('work_clouds.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Manage Work Cloud</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Book & Assignments --}}
                @canany(['subjects.index', 'topics.index'])
                <li class="nav-item {{ request()->routeIs('subjects.*', 'topics.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('subjects.*', 'topics.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-book-fill"></i>
                        <p>Book & Assignments<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('subjects.index')
                        <li class="nav-item">
                            <a href="{{ route('subjects.index') }}"
                               class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>View Subjects</p>
                            </a>
                        </li>
                        @endcan
                        @can('topics.index')
                        <li class="nav-item">
                            <a href="{{ route('topics.index') }}"
                               class="nav-link {{ request()->routeIs('topics.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>View Topics</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- User Management --}}
                @php
                    $hasUserManagement = auth()->user()->can('users.index') ||
                                        auth()->user()->can('roles.index') ||
                                        auth()->user()->can('permissions.index') ||
                                        auth()->user()->can('semesters.index');
                @endphp

                @if($hasUserManagement)
                @php
                    $userManagementRoutes = [
                        ['route' => 'users.index', 'permission' => 'users.index', 'label' => 'Users'],
                        ['route' => 'roles.index', 'permission' => 'roles.index', 'label' => 'Roles'],
                        ['route' => 'permissions.index', 'permission' => 'permissions.index', 'label' => 'Permissions'],
                        ['route' => 'semesters.index', 'permission' => 'semesters.index', 'label' => 'Semesters Setup']
                    ];
                @endphp

                <li class="nav-item {{ request()->routeIs('users.*','roles.*','permissions.*','semesters.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('users.*','roles.*','permissions.*','semesters.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>User Management<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach($userManagementRoutes as $item)
                            @can($item['permission'])
                            <li class="nav-item">
                                <a href="{{ route($item['route']) }}"
                                   class="nav-link {{ request()->routeIs(str_replace('.index', '.*', $item['route'])) ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>{{ $item['label'] }}</p>
                                </a>
                            </li>
                            @endcan
                        @endforeach
                    </ul>
                </li>
                @endif

                {{-- Hospital --}}
                @can('hospitals.index')
                <li class="nav-item {{ request()->routeIs('hospitals.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('hospitals.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-hospital-fill"></i>
                        <p>Hospital<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('hospitals.index') }}"
                               class="nav-link {{ request()->routeIs('hospitals.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Manage Hospitals</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Time Table --}}
                @can('timetable-events.index')
                <li class="nav-item {{ request()->routeIs('timetable-events.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('timetable-events.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar-event-fill"></i>
                        <p>Time Table<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('timetable-events.index') }}"
                               class="nav-link {{ request()->routeIs('timetable-events.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>List TimeTable</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Ads --}}
                @can('ads.index')
                <li class="nav-item {{ request()->routeIs('ads.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('ads.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-megaphone-fill"></i>
                        <p>Ads<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('ads.index') }}"
                               class="nav-link {{ request()->routeIs('ads.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Manage Ads</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Forms --}}
                @can('assignments.index')
                @php
                    $formsRoutes = [
                        'assignments.*', 'self-evaluations.*', 'trainee-evaluations.*',
                        'evaluation-360.*', 'rotation-evaluations.*', 'longitudinal-requirements.*'
                    ];

                    $formsMenu = [
                        ['route' => 'assignments.index', 'permission' => 'assignments.index', 'icon' => 'bi-journal-text', 'label' => 'Assignments'],
                        ['route' => 'self-evaluations.index', 'permission' => 'self-evaluations-index', 'icon' => 'bi-person-lines-fill', 'label' => 'Self Evaluations'],
                        ['route' => 'trainee-evaluations.index', 'permission' => 'trainee-evaluations-index', 'icon' => 'bi-person-check', 'label' => 'Trainee Evaluations'],
                        ['route' => 'evaluation-360.index', 'permission' => 'evaluation-360-index', 'icon' => 'bi-arrow-repeat', 'label' => '360° Evaluations'],
                        ['route' => 'rotation-evaluations.index', 'permission' => 'rotation-evaluations-index', 'icon' => 'bi-calendar-check', 'label' => 'Rotation Evaluations'],
                        ['route' => 'longitudinal-requirements.index', 'permission' => 'longitudinal-requirements-index', 'icon' => 'bi-clipboard-check', 'label' => 'Longitudinal Requirements']
                    ];
                @endphp

                <li class="nav-item {{ collect($formsRoutes)->contains(fn($r) => request()->routeIs($r)) ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ collect($formsRoutes)->contains(fn($r) => request()->routeIs($r)) ? 'active' : '' }}">
        <i class="nav-icon bi bi-journal-bookmark-fill"></i>
        <p>Forms<i class="nav-arrow bi bi-chevron-right"></i></p>
    </a>
    <ul class="nav nav-treeview">
        @foreach($formsMenu as $item)
            @can($item['permission'])
            <li class="nav-item">
                <a href="{{ route($item['route']) }}"
                   class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                    <i class="nav-icon bi {{ $item['icon'] }}"></i>
                    <p>{{ $item['label'] }}</p>
                </a>
            </li>
            @endcan
        @endforeach
    </ul>
</li>
                @endcan

                {{-- DOPS --}}
                @can('dops')
                @php
                    $dopsRoutes = [
                        'competencies.*', 'ratings.*', 'levels.*',
                        'diagnoses.*', 'procedures.*', 'dops.*'
                    ];

                    $dopsMenu = [
                        ['route' => 'competencies.index', 'icon' => 'bi-list-check', 'label' => 'Competencies'],
                        ['route' => 'ratings.index', 'icon' => 'bi-star-fill', 'label' => 'Ratings'],
                        ['route' => 'levels.index', 'icon' => 'bi-graph-up', 'label' => 'Levels'],
                        ['route' => 'diagnoses.index', 'icon' => 'bi-clipboard-pulse', 'label' => 'Diagnosis'],
                        ['route' => 'procedures.index', 'icon' => 'bi-tools', 'label' => 'Procedure'],
                        ['route' => 'dops.index', 'icon' => 'bi-journal-text', 'label' => 'DOPS']
                    ];
                @endphp

                <li class="nav-item {{ request()->routeIs(implode(',', $dopsRoutes)) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs(implode(',', $dopsRoutes)) ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bar-chart-line-fill"></i>
                        <p>DOPS<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach($dopsMenu as $item)
                        <li class="nav-item">
                            <a href="{{ route($item['route']) }}"
                               class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                <i class="nav-icon bi {{ $item['icon'] }}"></i>
                                <p>{{ $item['label'] }}</p>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endcan

                {{-- Trainee Specific Menus --}}
                @role('Trainee|Assessor')
                    {{-- PDFs --}}
                    @php $pdfGroups = \App\Models\Pdf::active()->get()->groupBy('page_name'); @endphp
                    @foreach($pdfGroups as $page_name => $pdfs)
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-pdf"></i>
                            <p>{{ $page_name }}<i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach($pdfs as $pdf)
                            <li class="nav-item">
                                <a href="{{ route('trainee.pdfs.show', ['page_name' => $pdf->title,'page_key' => $pdf->page_key, 'file' => 1]) }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ $pdf->title }}</p>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endforeach

                    {{-- Clinical Activities --}}
                    @php
                        $clinicalRoutes = [
                            'grand-ward-rounds.*', 'daily-ward-rounds.*',
                            'cicu-ward-rounds.*', 'trainee.dops.*'
                        ];

                        $clinicalMenu = [
                            ['route' => 'grand-ward-rounds.index', 'label' => 'Grand CICU / Ward Round'],
                            ['route' => 'daily-ward-rounds.index', 'label' => 'Daily Ward Round'],
                            ['route' => 'cicu-ward-rounds.index', 'label' => 'CICU Ward Rounds'],
                            ['route' => 'trainee.dops.index', 'label' => 'DOPS']
                        ];
                    @endphp

                    <li class="nav-item {{ request()->routeIs(implode(',', $clinicalRoutes)) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(implode(',', $clinicalRoutes)) ? 'active' : '' }}">
                            <i class="nav-icon bi bi-heart-pulse-fill"></i>
                            <p>Clinical Activities<i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach($clinicalMenu as $item)
                            <li class="nav-item">
                                <a href="{{ route($item['route']) }}"
                                   class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>{{ $item['label'] }}</p>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>

                    {{-- My Invitations --}}
                    @can('trainee.invitations')
                    <li class="nav-item {{ request()->routeIs('trainee.invitations.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('trainee.invitations.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-text"></i>
                            <p>My invitations<i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('trainee.invitations') }}"
                                   class="nav-link {{ request()->routeIs('trainee.invitations.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-circle"></i>
                                    <p>Trainee Invitations</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    {{-- Evaluation Form --}}
                    @php
                        $traineeEvaluationMenu = [
                            ['route' => 'evaluation-360.index', 'icon' => 'bi-arrow-repeat', 'label' => '360° Evaluations'],
                            ['route' => 'self-evaluations.index', 'icon' => 'bi-person-lines-fill', 'label' => 'Self Evaluations'],
                            ['route' => 'trainee-evaluations.index', 'icon' => 'bi-person-check', 'label' => 'Trainee Evaluations'],
                            ['route' => 'rotation-evaluations.index', 'icon' => 'bi-calendar-check', 'label' => 'Rotation Evaluations'],
                            ['route' => 'longitudinal-requirements.index', 'icon' => 'bi-clipboard-check', 'label' => 'Longitudinal Requirements']
                        ];
                    @endphp

                    <li class="nav-item {{ request()->routeIs('evaluation-360.*', 'self-evaluations.*', 'trainee-evaluations.*', 'rotation-evaluations.*', 'longitudinal-requirements.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('evaluation-360.*', 'self-evaluations.*', 'trainee-evaluations.*', 'rotation-evaluations.*', 'longitudinal-requirements.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-bookmark-fill"></i>
                            <p>Evaluation Form<i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @foreach($traineeEvaluationMenu as $item)
                            <li class="nav-item">
                                <a href="{{ route($item['route']) }}"
                                   class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                    <i class="nav-icon bi {{ $item['icon'] }}"></i>
                                    <p>{{ $item['label'] }}</p>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>

                    {{-- Exams --}}
                    <li class="nav-item">
                        <a href="{{ route('trainee.exams') }}"
                           class="nav-link {{ request()->routeIs('trainee.exams.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clipboard-check"></i>
                            <p>Exams</p>
                        </a>
                    </li>

                    {{-- My Results --}}
                    <li class="nav-item">
                        <a href="{{ route('trainee.results.index') }}"
                           class="nav-link {{ request()->routeIs('trainee.results.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clipboard-check"></i>
                            <p>My Results</p>
                        </a>
                    </li>
                @endrole

                {{-- QR Management --}}
                @canany(['qr-categories.index','qr-generates.index'])
                <li class="nav-item {{ request()->routeIs('qr-categories.*','qr-generates.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('qr-categories.*','qr-generates.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-qr-code"></i>
                        <p>QR Management<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('qr-categories.index')
                        <li class="nav-item">
                            <a href="{{ route('qr-categories.index') }}"
                               class="nav-link {{ request()->routeIs('qr-categories.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>QR Categories</p>
                            </a>
                        </li>
                        @endcan
                        @can('qr-generates.index')
                        <li class="nav-item">
                            <a href="{{ route('qr-generate.index') }}"
                               class="nav-link {{ request()->routeIs('qr-generate.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Generate QR</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                {{-- Settings --}}
                @can('settings')
                <li class="nav-item has-treeview {{ request()->routeIs('profile.*') || request()->routeIs('dashboard.password.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('profile.*','profile.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person"></i>
                        <p>Settings<i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pdfs.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-file-pdf"></i>
                                <p>Manage PDFs</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('password.edit') }}"
                               class="nav-link {{ request()->routeIs('dashboard.password.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-key"></i>
                                <p>Change Password</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                {{-- Logout --}}
                <li class="nav-item mt-3">
                    <a href="#" class="nav-link text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon bi bi-box-arrow-right"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
