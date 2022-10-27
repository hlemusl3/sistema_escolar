<?php require_once INCLUDES.'inc_header.php'; ?>

    <!-- DataTables Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Lista de recursos disponibles
            </h6>
        </div>
        <div class="card-body">
            <?php if (!empty($d->recursos)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th>Materia</th>
                                <th>Profesor</th>
                                <th>Título del recurso</th>
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
                                        <span class="text-dark"><strong><?php echo add_ellipsis($l->materia, 50); ?></strong></span>
                                    </td>
                                    <td>
                                        <span class="text-dark"><?php echo add_ellipsis($l->profesor, 50); ?></span>
                                    </td>
                                    <td>
                                        <span class="text-dark d-block"><?php echo add_ellipsis($l->titulo, 100); ?></span>
                                    </td>
                                    <td>
                                        <div class="float-right">
                                            <a class="btn btn-success btn-sm" href="<?php echo sprintf('assets/uploads/%s', $l->documento); ?>"><i class="fas fa-download"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
            <?php else: ?>
                <div class="py-5 text-center">
                    <img src="<?php echo get_image('homework.png'); ?>" alt="No hay registros" style="width: 150px;">
                    <p class="text-muted mt-3">No hay recursos disponibles.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php require_once INCLUDES.'inc_footer.php'; ?>