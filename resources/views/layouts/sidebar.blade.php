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
                @unlessrole('Admin')
                <li class="nav-item {{ request()->routeIs('profile.*','public.profile.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('profile.*','public.profile.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-circle"></i>
                        <p>
                            Profiles
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        {{-- My Profile --}}
                        <li class="nav-item">
                            <a href="{{ route('my.profile') }}"
                            class="nav-link {{ request()->routeIs('my.profile') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-person"></i>
                                <p>My Profile</p>
                            </a>
                        </li>

                        {{-- My Public Profile --}}
                        <li class="nav-item">
                            <a href="{{ route('public.profile', auth()->user()->id) }}"
                            class="nav-link {{ request()->routeIs('public.profile.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-globe"></i>
                                <p>Public Profile</p>
                            </a>
                        </li>

                    </ul>
                </li>
                @endunlessrole

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
                        'yes-no-options.*',
                        'form-types.*',
                        'rotations.*'
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
                            'yes-no-options.*',
                            'form-types.*',
                            'rotations.*'
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
                        {{-- <li class="nav-item">
                            <a href="{{ route('form-types.index') }}"
                            class="nav-link {{ request()->routeIs('form-types.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Form Types</p>
                            </a>
                        </li> --}}

                        <li class="nav-item">
                            <a href="{{ route('rotations.index') }}"
                            class="nav-link {{ request()->routeIs('rotations.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>Rotations</p>
                            </a>
                        </li>
                    </ul>
                </li>


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

                {{-- Timetable Events Dropdown --}}
                @can('timetable-events.index')
                <li class="nav-item {{ request()->routeIs('timetable-events.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('timetable-events.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar-event-fill"></i>
                        <p>
                            Time Table
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
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

                {{-- Ads Dropdown --}}
                @can('ads.index')
                <li class="nav-item {{ request()->routeIs('ads.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('ads.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-megaphone-fill"></i>
                        <p>
                            Ads
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
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

                {{-- Assignments Dropdown --}}
                @can('assignments.index')
                <li class="nav-item {{ request()->routeIs('assignments.*') ||
                                    request()->routeIs('self-evaluations.*') ||
                                   request()->routeIs('trainee-evaluations.*') ||
                                    request()->routeIs('evaluation-360.*') ||
                                    request()->routeIs('rotation-evaluations.*') ||
                                    request()->routeIs('longitudinal-requirements.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('assignments.*') ||
                                                request()->routeIs('self-evaluations.*') ||
                                                request()->routeIs('trainee-evaluations.*') ||
                                                request()->routeIs('evaluation-360.*') ||
                                                request()->routeIs('rotation-evaluations.*') ||
                                                request()->routeIs('longitudinal-requirements.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-journal-bookmark-fill"></i>
                        <p>
                            Forms
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <!-- Existing Assignments -->
                        @can('assignments.index')
                            <li class="nav-item">
                                <a href="{{ route('assignments.index') }}"
                                class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-journal-text"></i>
                                    <p>Assignments</p>
                                </a>
                            </li>
                        @endcan

                        <!-- Self Evaluations -->
                        {{-- @can('self-evaluations.index') --}}
                            <li class="nav-item">
                                <a href="{{ route('self-evaluations.index') }}"
                                class="nav-link {{ request()->routeIs('self-evaluations.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-person-lines-fill"></i>
                                    <p>Self Evaluations</p>
                                </a>
                            </li>
                        {{-- @endcan --}}
                        <!-- Trainee Evaluations -->
                        {{-- @can('trainee-evaluations.index') --}}
                            <li class="nav-item">
                                <a href="{{ route('trainee-evaluations.index') }}"
                                class="nav-link {{ request()->routeIs('trainee-evaluations.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-person-check"></i>
                                    <p>Trainee Evaluations</p>
                                </a>
                            </li>
                        {{-- @endcan --}}

                        <!-- 360° Evaluation -->
                        {{-- @can('evaluation-360.index') --}}
                            <li class="nav-item">
                                <a href="{{ route('evaluation-360.index') }}"
                                class="nav-link {{ request()->routeIs('evaluation-360.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-arrow-repeat"></i>
                                    <p>360° Evaluations</p>
                                </a>
                            </li>
                        {{-- @endcan --}}

                        <!-- Rotation Evaluations -->
                        {{-- @can('rotation-evaluations.index') --}}
                            <li class="nav-item">
                                <a href="{{ route('rotation-evaluations.index') }}"
                                class="nav-link {{ request()->routeIs('rotation-evaluations.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-calendar-check"></i>
                                    <p>Rotation Evaluations</p>
                                </a>
                            </li>
                        {{-- @endcan --}}

                        <!-- Longitudinal Requirements -->
                        {{-- @can('longitudinal-requirements.index') --}}
                            <li class="nav-item">
                                <a href="{{ route('longitudinal-requirements.index') }}"
                                class="nav-link {{ request()->routeIs('longitudinal-requirements.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-clipboard-check"></i>
                                    <p>Longitudinal Requirements</p>
                                </a>
                            </li>
                        {{-- @endcan --}}
                    </ul>
                </li>
                @endcan

                {{-- DOPS Dropdown --}}
                @can('dops')
<li class="nav-item {{
    request()->routeIs('competencies.*') ||
    request()->routeIs('ratings.*') ||
    request()->routeIs('levels.*') ||
    request()->routeIs('diagnoses.*') ||
    request()->routeIs('procedures.*') ||
    request()->routeIs('dops.*')
        ? 'menu-open' : '' }}">

    <a href="#" class="nav-link {{
        request()->routeIs('competencies.*') ||
        request()->routeIs('ratings.*') ||
        request()->routeIs('levels.*') ||
        request()->routeIs('diagnoses.*') ||
        request()->routeIs('procedures.*') ||
        request()->routeIs('dops.*')
            ? 'active' : '' }}">
        <i class="nav-icon bi bi-bar-chart-line-fill"></i>
        <p>
            DOPS
            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>
    </a>

    <ul class="nav nav-treeview">

        <li class="nav-item">
            <a href="{{ route('competencies.index') }}"
               class="nav-link {{ request()->routeIs('competencies.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-list-check"></i>
                <p>Competencies</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('ratings.index') }}"
               class="nav-link {{ request()->routeIs('ratings.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-star-fill"></i>
                <p>Ratings</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('levels.index') }}"
               class="nav-link {{ request()->routeIs('levels.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-graph-up"></i>
                <p>Levels</p>
            </a>
        </li>

        {{-- 🔹 Diagnosis --}}
        <li class="nav-item">
            <a href="{{ route('diagnoses.index') }}"
               class="nav-link {{ request()->routeIs('diagnoses.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-clipboard-pulse"></i>
                <p>Diagnosis</p>
            </a>
        </li>

        {{-- 🔹 Procedure --}}
        <li class="nav-item">
            <a href="{{ route('procedures.index') }}"
               class="nav-link {{ request()->routeIs('procedures.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-tools"></i>
                <p>Procedure</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('dops.index') }}"
               class="nav-link {{ request()->routeIs('dops.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-journal-text"></i>
                <p>DOPS</p>
            </a>
        </li>

    </ul>
</li>
@endcan



        @role('Trainee')
                @php
                    // Active PDFs grouped by page_name
                    $pdfGroups = \App\Models\Pdf::active()->get()->groupBy('page_name');
                @endphp

                @foreach($pdfGroups as $page_name => $pdfs)
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-pdf"></i>
                        <p>
                            {{ $page_name }}
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
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
                {{-- @can('grand-ward-rounds.index') --}}
                    <li class="nav-item {{
    request()->routeIs(
        'grand-ward-rounds.*',
        'daily-ward-rounds.*',
        'cicu-ward-rounds.*',
        'trainee.dops.*'
    ) ? 'menu-open' : '' }}">

    <a href="#" class="nav-link {{
        request()->routeIs(
            'grand-ward-rounds.*',
            'daily-ward-rounds.*',
            'cicu-ward-rounds.*',
            'trainee.dops.*'
        ) ? 'active' : '' }}">
        <i class="nav-icon bi bi-heart-pulse-fill"></i>
        <p>
            Clinical Activities
            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>
    </a>

    <ul class="nav nav-treeview">
        {{-- Grand CICU / Ward Round --}}
        <li class="nav-item">
            <a href="{{ route('grand-ward-rounds.index') }}"
               class="nav-link {{ request()->routeIs('grand-ward-rounds.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Grand CICU / Ward Round</p>
            </a>
        </li>

        {{-- Daily Ward Round --}}
        <li class="nav-item">
            <a href="{{ route('daily-ward-rounds.index') }}"
               class="nav-link {{ request()->routeIs('daily-ward-rounds.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Daily Ward Round</p>
            </a>
        </li>

        {{-- CICU Ward Rounds --}}
        <li class="nav-item">
            <a href="{{ route('cicu-ward-rounds.index') }}"
               class="nav-link {{ request()->routeIs('cicu-ward-rounds.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>CICU Ward Rounds</p>
            </a>
        </li>

        {{-- 🔹 DOPS (Trainee) --}}
        <li class="nav-item">
            <a href="{{ route('trainee.dops.index') }}"
               class="nav-link {{ request()->routeIs('trainee.dops.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-journal-check"></i>
                <p>DOPS</p>
            </a>
        </li>

    </ul>
</li>

                    {{-- @endcan --}}
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
                {{-- QR Management --}}
                @canany(['qr-categories.index','qr-generates.index'])
                <li class="nav-item {{ request()->routeIs('qr-categorie.*','qr-generate.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('qr-categories.*','qr-generates.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-qr-code"></i>
                        <p>
                            QR Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        {{-- QR Category --}}
                        @can('qr-categories.index')
                        <li class="nav-item">
                            <a href="{{ route('qr-categories.index') }}"
                            class="nav-link {{ request()->routeIs('qr-categories.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-circle"></i>
                                <p>QR Categories</p>
                            </a>
                        </li>
                        @endcan

                        {{-- QR Generate --}}
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


                @can('settings')
                <li class="nav-item has-treeview {{ request()->routeIs('profile.*') || request()->routeIs('dashboard.password.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('profile.*','profile.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person"></i>
                        <p>
                            Settings
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
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
