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

<!-- Nav Item - Lecciones -->
<li class="nav-item <?php echo $slug === 'alumno-lecciones' ? 'active' : null; ?>">
    <a href="alumno/lecciones" class="nav-link">
        <i class="fas fa-fw fa-chalkboard-teacher"></i>
        <span>Lecciones</span></a>
</li>

<!-- Nav Item - Tareas -->
<li class="nav-item <?php echo $slug === 'alumno-tareas' ? 'active' : null; ?>">
    <a href="alumno/tareas" class="nav-link">
        <i class="fas fa-fw fa-layer-group"></i>
        <span>Tareas</span></a>
</li>

<!-- Nav Item - Grupo del alumno -->
<li class="nav-item <?php echo $slug === 'alumno-grupo' ? 'active' : null; ?>">
    <a href="alumno/grupo" class="nav-link">
        <i class="fas fa-fw fa-users"></i>
        <span>Grupo</span></a>
</li>

            <!-- Foros -->
            <li class="nav-item <?php echo $slug === 'foros' ? 'active' : null; ?>">
                <a class="nav-link" href="alumno/foros">
                    <i class="fas fa-fw fa-comments"></i>
                    <span>Foros</span></a>
            </li>

            <!-- Biblioteca -->
            <li class="nav-item <?php echo $slug === 'biblioteca' ? 'active' : null; ?>">
                <a class="nav-link" href="biblioteca">
                    <i class="fas fa-fw fa-swatchbook"></i>
                    <span>Biblioteca</span></a>
            </li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Sidebar Toggle (sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>