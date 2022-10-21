<?php require_once INCLUDES.'inc_header.php'; ?>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#alumno_data" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="alumno_data">
                    <h6 class="m-0 font-weight-bold text-primary">Detalles de la entrega <div class="float-right"><?php echo sprintf('Entregado el %s', $d->entrega->fecha_entregado)?></div></h6>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse show" id="alumno_data">
                    <div class="card-body">
                        <?php echo insert_inputs();?>
                        
                        <div class="form-group">
                            <label for="nombres">Nombre del alumno</label>
                            <input type="text" class="form-control" value="<?php echo $d->alumno->nombre_completo ?>" disabled>
                        </div>

                        <?php if(!empty($d->entrega->comentario)): ?>
                            <div class="form-group">
                                <label for="comentario">Comentario</label>
                                <div class="card p-3">
                                    <?php echo nl2br($d->entrega->comentario); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="form-group">
                                <label for="comentario">Comentario</label>
                                <div class="card p-3">
                                    Sin comentario
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($d->entrega->enlace)):?>
                            <div class="form-group">
                                <label for="enlace">Enlace</label> <br>
                                <a href="<?php echo $d->entrega->enlace?>" target="_blank">ir al enlace</a>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($d->entrega->documento)):?>
                            <div class="form-group">
                                <label for="enlace">Documento</label> <br>
                                    <?php if(is_file(UPLOADS.$d->entrega->documento)): ?>
                                            <a href="<?php echo sprintf('assets/uploads/%s', $d->entrega->documento)?>">
                                                <div class="badge badge-info btn-primary"><i class="fa fa-download"></i> Click Aqui para descargar</div>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo get_image('broken.png'); ?>" data-lightbox="Documento" title="<?php echo sprintf('No se encontro el documento.')?>">
                                                <img src="<?php echo get_image('broken.png'); ?>" alt="<?php echo sprintf('Documento')?>" class="img-fluid img-thumbnail">
                                            </a>
                                            <p class="text-muted"><?php echo sprintf('El archivo %s no existe o está dañado.', $d->entrega->documento)?></p>
                                    <?php endif; ?>                                
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>            
        </div>
    </div>
<?php require_once INCLUDES.'inc_footer.php'; ?>