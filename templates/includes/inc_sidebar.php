<!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo URL; ?>">
                <div class="sidebar-brand-icon">
                  <img src="<?php echo get_image('logo_1000.png'); ?>" alt="<?php echo get_sitename(); ?>" class="img-fluid">
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <?php if (is_admin(get_user_role())): ?>
                <li class="nav-item active">
                <a class="nav-link" href="dashboard">
                    <i class="fas fa-fw fa-user-lock"></i>
                    <span>Dirección</span></a>
            </li>
            <?php endif;?>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
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
            <li class="nav-item">
                <a class="nav-link" href="profesores">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Profesores</span></a>
            </li>

            <!-- Alumnos -->
            <li class="nav-item">
                <a class="nav-link" href="alumnos">
                    <i class="fas fa-fw fa-book-reader"></i>
                    <span>Alumnos</span></a>
            </li>

            <!-- Materias -->
            <li class="nav-item">
                <a class="nav-link" href="materias">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Materias</span></a>
            </li>

            <!-- Grupos -->
            <li class="nav-item">
                <a class="nav-link" href="grupos">
                    <i class="fas fa-fw fa-graduation-cap"></i>
                    <span>Grupos</span></a>
            </li>

            <!-- Horarios -->
            <li class="nav-item">
                <a class="nav-link" href="horarios">
                    <i class="fas fa-fw fa-calendar-alt"></i>
                    <span>Horarios</span></a>
            </li>

            <!-- Lecciones -->
            <li class="nav-item">
                <a class="nav-link" href="lecciones">
                    <i class="fas fa-fw fa-chalkboard-teacher"></i>
                    <span>Lecciones</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message 
            <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
                <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
            </div>-->

    </ul>
<!-- End of Sidebar -->
