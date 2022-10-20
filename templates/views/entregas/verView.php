<?php require_once INCLUDES.'inc_header.php'; ?>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Entrega de los alumnos</h6>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($d->entregas->rows)): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Nombre completo</th>
                                                <th width="20%">Entrega</th>
                                                <th width="20%">Fecha de entrega</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($d->entregas->rows as $e): ?>
                                            <tr>
                                                <td><?php echo $e->nombre_alumno; ?></td>
                                                <td> <a href="<?php echo sprintf('entregas/detalle/%s', $e->id)?>">Ver entrega</a> </td>
                                                <td><?php echo format_date($e->fecha_entregado);?></td>
                                            </tr>
                                            
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php echo $d->entregas->pagination; ?>
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