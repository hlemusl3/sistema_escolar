<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <!-- DataTales Example -->
    <div class="col-xl-12 col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $d->title; ?></h6>
            </div>
            <div class="card-body">
                <?php if(!empty($d->lecciones->rows)): ?>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="row">
                                   <div class="col-xl-1">
                                    </div>
                                    <div class="col-xl-4">
                                        Título de la lección
                                    </div>

                                    <div class="col-xl-2">
                                        Estado
                                    </div>

                                    <div class="col-xl-3">
                                        Fecha máxima
                                    </div>

                                    <div class="col-xl-2 text-right">
                                        Acción
                                    </div>

                            </div>
                        </li>
                        <?php foreach ($d->lecciones->rows as $l): ?>
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-xl-1">
                                        <a href="<?php echo sprintf('lecciones/ver_admin/%s', $l->id); ?>" >
                                            <img src="<?php echo get_image('player.png'); ?>" alt="<?php echo $l->titulo;?>" class="img-fluid" style="width: 30px;">
                                        </a>
                                    </div>
                                    <div class="col-xl-4">
                                        <span class="text-dark">
                                            <?php echo add_ellipsis($l->titulo, 100); ?>
                                            <?php if(!empty($l->video)): ?>
                                                <span>
                                                    <div class="badge badge-pull badge-warning"><i class="fas fa-video"></i> Tiene video</div>
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                        <small class="d-block text-muted"><?php echo sprintf('<strong>%s</strong> / %s', $l->profesor, $l->materia); ?></small>
                                    </div>

                                    <div class="col-xl-2">
                                    <span class="text-dark">
                                            <?php echo format_estado_leccion($l->status); ?>
                                        </span>
                                        
                                    </div>

                                    <div class="col-xl-3">
                                    <span class="text-dark">
                                            <?php echo format_date($l->fecha_disponible); ?>
                                        </span>
                                        
                                    </div>

                                    <div class="col-xl-2">
                                        <div class="btn-group float-right">
                                        <a class="btn btn-success btn-sm" href="<?php echo sprintf('lecciones/ver_admin/%s', $l->id); ?>"><i class="fas fa-eye"></i></a>                                        
                                        <a class="btn btn-danger btn-sm confirmar" href="<?php echo buildURL(sprintf('lecciones/borrar/%s', $l->id)); ?>"><i class="fas fa-trash"></i></a>                                        
                                        </div>    
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php echo $d->lecciones->pagination; ?>
                <?php else: ?>
                <div class="py-5 text-center">
                    <img src="<?php echo get_image('homework.png'); ?>" alt="No hay registro" style="width: 150px;">
                    <p class="text-muted mt-3">No hay lecciones disponibles.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div> 

</div>


<?php require_once INCLUDES.'inc_footer.php'; ?>