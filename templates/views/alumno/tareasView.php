<?php require_once INCLUDES.'inc_header.php'; ?>

    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Lista de tareas disponibles
            </h6>
        </div>
        <div class="card-body">
            <?php if (!empty($d->tareas)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <td width="5"></td>
                                <td>Materia</td>
                                <td>Profesor</td>
                                <td>Título de la tarea</td>
                                <td width="15%">Disponible el</td>
                                <td width="15%">Tienes hasta el</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($d->tareas as $t): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo sprintf('alumno/tarea/%s', $t->id); ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="text-dark"><strong><?php echo add_ellipsis($t->materia, 50); ?></strong></span>
                                    </td>
                                    <td>
                                        <span class="text-dark"><?php echo add_ellipsis($t->profesor, 50); ?></span>                                        
                                    </td>
                                    <td>
                                        <span class="text-dark d-block"><?php echo add_ellipsis($t->titulo, 100); ?></span>
                                    </td>
                                    <td>
                                        <span class="text-dark d-block"><?php echo format_date($t->fecha_inicial); ?></span>
                                        <?php if ((strtotime($t->fecha_disponible) - time()) > 0): ?>
                                            <?php if ((strtotime($t->fecha_inicial) - time()) < 0): ?>
                                                <span class="text-white badge badge-success">Ya disponible.</span>
                                            <?php else: ?>
                                                <span class="text-white badge badge-danger">No disponible aún.</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-dark d-block"><?php echo format_date($t->fecha_disponible); ?></span>
                                        
                                        <?php if ((strtotime($t->fecha_disponible) - time()) < 0): ?>
                                            <span class="text-white badge badge-danger">No disponible ya.</span>
                                        <?php else: ?>
                                            <span class="text-white badge badge-warning"><?php echo format_tiempo_restante($t->fecha_disponible); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
            <?php else: ?>
                <div class="py-5 text-center">
                    <img src="<?php echo get_image('homework.png'); ?>" alt="No hay registros" style="width: 150px;">
                    <p class="text-muted mt-3">No hay tareas disponibles.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php require_once INCLUDES.'inc_footer.php'; ?>