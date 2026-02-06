<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">

    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ms-auto">
      <!-- CV Builder Button -->
      <li class="nav-item">
        <a class="nav-link" href="{{ route('cv.dashboard') }}">
          <i class="bi bi-file-earmark-person me-1"></i>
          CV Builder
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="bi bi-search"></i>
        </a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-chat-text"></i>
          <span class="navbar-badge badge text-bg-danger">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <a href="#" class="dropdown-item">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <img
                  src="{{ asset('adminlte/assets/img/user1-128x128.jpg') }}"
                  class="img-size-50 rounded-circle me-3"
                />
              </div>
              <div class="flex-grow-1">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-end fs-7 text-danger">
                    <i class="bi bi-star-fill"></i>
                  </span>
                </h3>
                <p class="fs-7">Call me whenever you can...</p>
                <p class="fs-7 text-secondary">
                  <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                </p>
              </div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-bell-fill"></i>
          <span class="navbar-badge badge text-bg-warning">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-envelope me-2"></i> 4 new messages
            <span class="float-end text-secondary fs-7">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display:none"></i>
        </a>
      </li>

      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">          
          <!-- Profile Image -->
          <img
            src="{{ asset('adminlte/assets/img/user2-160x160.jpg') }}"
            class="user-image rounded-circle shadow me-2"
            width="35"
            alt="User Image"
          />
          <span class="d-none d-md-inline fw-semibold">
            {{ auth()->user()->name }}
          </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">

          <!-- User Header -->
          <li class="user-header text-bg-primary text-center py-4">
            <img
              src="{{ asset('adminlte/assets/img/user2-160x160.jpg') }}"
              class="rounded-circle shadow mb-2"
              width="90"
            />
            <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
            <small class="d-block">
              {{ auth()->user()->getRoleNames()->first() }}
            </small>
            <small>
              Member since {{ auth()->user()->created_at->format('M Y') }}
            </small>
          </li>

          <!-- Footer Buttons -->
          <li class="user-footer px-3 py-3">

            @if(auth()->user()->hasRole('Admin'))

                <!-- Admin UI -->
                <div class="d-grid">
                  <a href="#" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-gear me-1"></i> Settings
                  </a>
                </div>

            @else

                <!-- Normal User UI -->
                <div class="d-grid gap-2">
                  <a href="{{ route('my.profile') }}" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-person me-1"></i> My Profile
                  </a>

                  <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                  </a>
                </div>

            @endif

            <hr class="my-3">

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <div class="d-grid">
                <button type="submit" class="btn btn-outline-danger btn-sm">
                  <i class="bi bi-box-arrow-right me-1"></i> Sign out
                </button>
              </div>
            </form>

          </li>
        </ul>
      </li>

    </ul>
  </div>
</nav>
