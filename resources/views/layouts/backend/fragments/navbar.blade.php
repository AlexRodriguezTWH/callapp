<ul class="navbar-nav">
  <li class="nav-item">
    <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
  </li>

  @if (Session::get('opcion_page_envivo'))
  <li class="nav-item d-none d-sm-inline-block">
    <a href="{{ route('tickets.envivo') }}" class="nav-link text-white">Tickets en vivo</a>
  </li>
  @endif
</ul>
