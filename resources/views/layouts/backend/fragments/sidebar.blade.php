<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 ">
  <!-- Brand Logo -->
  <a href="{{ route('home') }}" class="brand-link">
    <img src="{{ asset('adminlte/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"> Control Tickets </span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          @if (Session::get('opcion_page_envivo'))
         <li class="nav-item">
           <a href="{{ route('tickets.envivo') }}" class="nav-link">
             <i class="nav-icon fas fa-play"></i>
             <p>
               Tickets en vivo
             </p>
           </a>
         </li>
         @endif

         @if (Session::get('opcion_page_pendiente'))
         <li class="nav-item">
           <a href="{{ route('tickets.pendientes') }}" class="nav-link">
             <i class="nav-icon fas fa-tasks"></i>
             <p class="text-bold">
               Tickets pendientes
             </p>
           </a>
         </li>
         @endif

         @if (Session::get('opcion_page_historial'))
         <li class="nav-item">
           <a href="{{ route('tickets.historial') }}" class="nav-link">
             <i class="nav-icon fas fa-clipboard-list"></i>
             <p class="text-bold">
               Historial
             </p>
           </a>
         </li>
         @endif

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
