<?php require_once INCLUDES.'inc_header.php'; ?>

    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Lista de tareas disponibles
            </h6>
        </div>
        <div class="card-body">
            <?php if (!empty($d->tareas->rows)): ?>
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xl-1">
                            </div>
                            <div class="col-xl-2">
                                Materia
                            </div>
                            <div class="col-xl-2">
                                Profesor
                            </div>
                            <div class="col-xl-3">
                                Titulo de la tarea
                            </div>
                            <div class="col-xl-2">
                                Disponible el
                            </div>
                            <div class="col-xl-2 text-right">
                                Tienes hasta el
                            </div>
                        </div>
                    </li>
                    <?php foreach($d->tareas->rows as $t): ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-xl-1">
                                    <a href="<?php echo sprintf('alumno/tarea/%s', $t->id); ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                <div class="col-xl-2">
                                    <span class="text-dark"><strong><?php echo add_ellipsis($t->materia, 50); ?></strong></span>
                                </div>
                                <div class="col-xl-2">
                                    <span class="text-dark"><?php echo add_ellipsis($t->profesor, 50); ?></span>
                                </div>
                                <div class="col-xl-3">
                                    <span class="text-dark d-block"><?php echo add_ellipsis($t->titulo, 100); ?></span>
                                </div>
                                <div class="col-xl-2">
                                    <span class="text-dark d-block"><?php echo format_date($t->fecha_inicial); ?></span>

                                    <?php if ((strtotime($t->fecha_disponible) - time()) > 0): ?>
                                        <?php if ((strtotime($t->fecha_inicial) - time()) < 0): ?>
                                            <span class="text-white badge badge-success">Ya disponible.</span>
                                        <?php else: ?>
                                            <span class="text-white badge badge-danger">No disponible aún.</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                </div>
                                <div class="col-xl-2 text-right">
                                    <span class="text-dark d-block"><?php echo format_date($t->fecha_disponible); ?></span>

                                    <?php if ((strtotime($t->fecha_disponible) - time()) < 0): ?>
                                        <span class="text-white badge badge-danger">No disponible ya.</span>
                                    <?php else: ?>
                                        <span class="text-white badge badge-warning"><?php echo format_tiempo_restante($t->fecha_disponible); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <?php echo $d->tareas->pagination; ?>
            <?php else: ?>
                <div class="py-5 text-center">
                    <img src="<?php echo get_image('homework.png'); ?>" alt="No hay registros" style="width: 150px;">
                    <p class="text-muted mt-3">No hay tareas disponibles.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php require_once INCLUDES.'inc_footer.php'; ?>