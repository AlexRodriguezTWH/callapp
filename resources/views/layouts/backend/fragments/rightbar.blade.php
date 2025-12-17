<ul class="navbar-nav ml-auto">
  <!-- Navbar Search -->
  <!-- <li class="nav-item">
    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
      <i class="fas fa-search"></i>
    </a>
    <div class="navbar-search-block">
      <form class="form-inline">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
      </form>
    </div>
  </li> -->

  <li class="nav-item">
    <a class="nav-link text-white" data-widget="fullscreen" href="#" role="button">
      <i class="fas fa-expand-arrows-alt"></i>
    </a>
  </li>
  <li class="nav-item dropdown">

      <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          @if( session('gr_user') )
            {{ session('gr_user') }}
          @else
            {{ Auth::user()->name }}
          @endif
      </a>

      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

        <!-- <a class="dropdown-item" href="/">
            Cambiar contraseña
        </a> -->

          <a class="dropdown-item" href="{{ route('gr.logout') }}">
              Salir
          </a>
      </div>
  </li>

  <!-- <li class="nav-item">
    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
      <i class="fas fa-th-large"></i>
    </a>
  </li> -->
</ul>
