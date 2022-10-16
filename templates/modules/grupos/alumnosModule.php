<?php if (!empty($d)): ?>
    <ul class="list-group">
        <?php foreach ($d as $a): ?>
            <li class="list-group-item">
                <div class="btn-group float-right">
                    <a class="btn btn-success btn-sm" href="<?php echo sprintf('mailto:%s?subject=[%s] - Hola %s', $a->email, get_sitename(), $a->nombre_completo); ?>"><i class="fas fa-envelope"></i></a>
                    <?php if ($a->status === 'suspendido'): ?>
                        <button class="btn btn-warning text-dark btn-sm remover_suspension_alumno" data-id="<?php echo $a->id; ?>"><i class="fas fa-undo"></i></button>
                    <?php else: ?>
                        <?php if($a->status === 'pendiente'): ?>
                            <button class="btn btn-danger btn-sm suspender_alumno" data-id="<?php echo $a->id; ?>" disabled><i class="fas fa-ban"></i></button>
                        <?php else: ?>
                            <button class="btn btn-danger btn-sm suspender_alumno" data-id="<?php echo $a->id; ?>"><i class="fas fa-ban"></i></button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <button class="btn btn-danger btn-sm quitar_alumno_grupo" data-id="<?php echo $a->id; ?>"><i class="fas fa-trash"></i></button>
                </div>
                <a href="<?php echo sprintf('alumnos/ver/%s', $a->id); ?>" target="_blank"><b><?php echo $a->nombre_completo; ?></b></a>
                <br>
                <?php if ($a->status === 'suspendido'): ?>
                    <span class="badge badge-pill badge-danger text-white">Suspendido</span>
                <?php else: ?>
                    <?php if ($a->status === 'pendiente'): ?>
                        <span class="badge badge-pill badge-warning text-dark">Pendiente</span>
                    <?php else: ?>
                        <span class="badge badge-pill badge-success text-white">Activo</span>
                    <?php endif;?>
                <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="text-center py-5">
        <img src="<?php echo get_image('undraw_taken.png'); ?>" alt="No hay registros." class="img-fluid" style="width: 200px;">
        <p class="text-muted">No hay alumnos asignados al grupo.</p>
    </div>
<?php endif; ?>