            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php echo $slug === 'dashboard' ? 'active' : null; ?>">
                <a class="nav-link" href="dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <li class="nav-item <?php echo $slug === 'mensajes' ? 'active' : null; ?>">
                <a class="nav-link" href="mensajes">
                    <i class="fas fa-fw fa-envelope"></i>
                    <span>Mis mensajes</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Menú
            </div>

            <!-- Grupos -->
            <li class="nav-item <?php echo $slug === 'grupos' ? 'active' : null; ?>">
                <a class="nav-link" href="grupos/asignados">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Grupos</span></a>
            </li>

            <!-- Materias -->
            <li class="nav-item <?php echo $slug === 'materias' ? 'active' : null; ?>">
                <a class="nav-link" href="materias/asignadas">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Materias</span></a>
            </li>

            <!-- Alumnos -->
            <li class="nav-item <?php echo $slug === 'alumnos' ? 'active' : null; ?>">
                <a class="nav-link" href="alumnos/asignados">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Alumnos</span></a>
            </li>

            <!-- Mis lecciones -->
            <li class="nav-item <?php echo $slug === 'lecciones' ? 'active' : null; ?>">
                <a class="nav-link" href="lecciones/mislecciones">
                    <i class="fas fa-fw fa-chalkboard-teacher"></i>
                    <span>Lecciones</span></a>
            </li>

            <!-- Mis tareas -->
            <li class="nav-item <?php echo $slug === 'tareas' ? 'active' : null; ?>">
                <a class="nav-link" href="tareas/mistareas">
                    <i class="fas fa-fw fa-layer-group"></i>
                    <span>Tareas</span></a>
            </li>

            <!-- Foros -->
            <li class="nav-item <?php echo $slug === 'foros' ? 'active' : null; ?>">
                <a class="nav-link" href="foros">
                    <i class="fas fa-fw fa-comments"></i>
                    <span>Foros</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>