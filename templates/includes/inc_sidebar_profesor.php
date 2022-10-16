            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php echo $slug === 'dashboard' ? 'active' : null; ?>">
                <a class="nav-link" href="dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menú
            </div>

            <!-- Profesores -->
            <li class="nav-item <?php echo $slug === 'grupos' ? 'active' : null; ?>">
                <a class="nav-link" href="grupos/asignados">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Grupos</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>