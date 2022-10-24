<?php require_once INCLUDES.'inc_header.php'; ?>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"><?php echo $d->title; ?></h6>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($d->alumnos)): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Nombre completo</th>
                                                <th>Grupos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($d->alumnos as $a): ?>
                                            <tr>
                                                <td><a href="<?php echo sprintf('alumnos/detalle/%s', $a->id_alumno)?>" class="text-muted"><?php echo empty($a->alumno) ? '<span class="text-muted">Sin nombre</span>' : add_ellipsis($a->alumno, 50); ?></a></td>
                                                <td><a href="<?php echo sprintf('grupos/detalles/%s', $a->id_grupo)?>"><?php echo empty($a->grupo) ? '<span class="text-muted">Sin nombre</span>' : add_ellipsis($a->grupo, 50); ?></a></td>
                                            
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                            <div class="py-5 text-center">
                                <img src="<?php echo IMAGES.'undraw_empty.png' ?>" alt="No hay registro" style="width: 250px;">
                                <p class="text-muted">No hay registros en la base de datos</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

<?php require_once INCLUDES.'inc_footer.php'; ?>