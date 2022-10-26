<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <!-- DataTales Example -->
    <div class="col-xl-12 col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><?php echo $d->title; ?></h6>
            </div>
            <div class="card-body">
                <?php if(!empty($d->recursos)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="10%"></th>
                                    <th>Título del recurso</th>
                                    <th width="10%">Estado</th>
                                    <th width="10%">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($d->recursos as $l): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo sprintf('assets/uploads/%s', $l->documento); ?>" >
                                                <img src="<?php echo get_image('yumi_dir.png'); ?>" alt="<?php echo $l->titulo;?>" class="img-fluid" style="width: 30px;">
                                            </a>
                                        </td>
                                        <td>
                                            <span class="text-dark">
                                                <?php echo add_ellipsis($l->titulo, 100); ?>
                                                <?php if(!empty($l->video)): ?>
                                                    <span>
                                                        <div class="badge badge-pull badge-warning"><i class="fas fa-video"></i> Tiene video</div>
                                                    </span>
                                                <?php endif; ?>
                                            </span>
                                            <small class="d-block text-muted"><?php echo sprintf('<strong>%s</strong> / %s', $l->profesor, $l->materia); ?></small>   
                                        </td>
                                        <td>
                                            <span class="text-dark">
                                                <?php echo format_estado_leccion($l->status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group float-right">
                                            <a class="btn btn-success btn-sm" href="<?php echo sprintf('assets/uploads/%s', $l->documento); ?>"><i class="fas fa-download"></i></a>
                                            <a class="btn btn-danger btn-sm confirmar" href="<?php echo buildURL(sprintf('biblioteca/borrar/%s', $l->id)); ?>"><i class="fas fa-trash"></i></a>                                        
                                            </div>    
                                        </td>
                                    </tr>
                                    
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                <div class="py-5 text-center">
                    <img src="<?php echo get_image('homework.png'); ?>" alt="No hay registro" style="width: 150px;">
                    <p class="text-muted mt-3">No hay recursos disponibles.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div> 

</div>


<?php require_once INCLUDES.'inc_footer.php'; ?>