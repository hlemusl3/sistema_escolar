<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <!-- DataTales Example -->
    <div class="col-xl-6 col-md-6 col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $d->title1; ?></h6>
            </div>
            <div class="card-body">
                <?php if(!empty($d->lecciones->rows)): ?>
                    <ul class="list-group">
                        <?php foreach ($d->lecciones->rows as $l): ?>
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-xl-1">
                                        <a href="<?php echo sprintf('lecciones/ver/%s', $l->id); ?>" >
                                            <img src="<?php echo get_image('player.png'); ?>" alt="<?php echo $l->titulo;?>" class="img-fluid" style="width: 50px;">
                                        </a>
                                    </div>
                                    <div class="col-xl-9">
                                        <span class="text-dark">
                                            <?php echo add_ellipsis($l->titulo, 100); ?>
                                        </span>
                                    </div>
                                    <div class="col-xl-2">
                                        <div class="btn-group float-right">
                                        <a class="btn btn-success btn-sm" href="<?php echo sprintf('lecciones/editar/%s', $l->id); ?>"><i class="fas fa-edit"></i></a>                                        
                                        <a class="btn btn-success btn-sm" href="<?php echo sprintf('lecciones/ver/%s', $l->id); ?>"><i class="fas fa-eye"></i></a>                                        
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
                    <p class="text-muted mt-3">No hay lecciones para esta materia.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div> 

    <!-- DataTales Tareas -->
    <div class="col-xl-6 col-md-6 col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $d->title2; ?></h6>
            </div>
            <div class="card-body">
                <?php if(!empty($d->tareas->rows)): ?>
                    <ul class="list-group">
                        <?php foreach ($d->tareas->rows as $l): ?>
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-xl-1">
                                        <a href="<?php echo sprintf('tareas/ver/%s', $l->id); ?>" >
                                            <img src="<?php echo get_image('homework2.png'); ?>" alt="<?php echo $l->titulo;?>" class="img-fluid" style="width: 50px;">
                                        </a>
                                    </div>
                                    <div class="col-xl-9">
                                        <span class="text-dark">
                                            <?php echo add_ellipsis($l->titulo, 100); ?>
                                        </span>
                                    </div>
                                    <div class="col-xl-2">
                                        <div class="btn-group float-right">
                                        <a class="btn btn-success btn-sm" href="<?php echo sprintf('tareas/editar/%s', $l->id); ?>"><i class="fas fa-edit"></i></a>                                        
                                        <a class="btn btn-success btn-sm" href="<?php echo sprintf('tareas/ver/%s', $l->id); ?>"><i class="fas fa-eye"></i></a>
                                        <a class="btn btn-danger btn-sm confirmar" href="<?php echo buildURL(sprintf('tareas/borrar/%s', $l->id)); ?>"><i class="fas fa-trash"></i></a>                                        
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
                    <p class="text-muted mt-3">No hay tareas asignadas a esta materia.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div> 

</div>


<?php require_once INCLUDES.'inc_footer.php'; ?>