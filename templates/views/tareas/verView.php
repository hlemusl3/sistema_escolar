<?php require_once INCLUDES.'inc_header.php'; ?>
    
    <div class="row">
        <div class="col-12">
            <!-- Detalles de la Tarea -->
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold text-primary">
                    <?php echo sprintf('Tarea / <b>%s</b>', $d->t->materia); ?>
                    
                    <a href="<?php echo sprintf('grupos/materia/%s',$d->t->id_materia); ?>" class="btn btn-primary btn-sm float-right"><i class="fas fa-undo"></i> Regresar</a>
                </div>
                <div class="card-doby">
                    <h2><strong><?php echo $d->t->titulo; ?></strong></h2>
                </div>
                <div class="card-footer">
                    <?php echo format_estado_tarea($d->t->status); ?>

                    <span class="float-right"><?php echo sprintf('Disponible hasta el <b>%s</b>.', format_date($d->t->fecha_disponible)); ?></span>
                </div>
            </div>
            
            <!-- instrucciones de la Tarea -->
            <div class="card shadow mb-4">
                    <div class="card-header font-weight-bold text-primary">Instrucciones</div>
                    <div class="card-body">
                        <?php echo nl2br($d->t->instrucciones); ?>                        
                    </div>
                </div>
                            
            <?php if (!empty($d->t->enlace)): ?>
                <!-- Enlace de la Tarea -->
                <div class="card shadow mb-4">
                    <div class="card-header font-weight-bold text-primary">Enlace disponible</div>
                    <div class="card-body">
                        <a href="<?php echo sprintf('%s', $d->t->enlace)?>" target="_blank"><?php echo sprintf('%s', $d->t->enlace)?></a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- documento de la Tarea -->
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold text-primary">Documento disponible</div>
                <div class="card-body">
                    <?php if (!empty($d->t->documento)): ?>
                        <?php if(is_file(UPLOADS.$d->t->documento)): ?>
                            <a href="<?php echo sprintf('assets/uploads/%s', $d->t->documento)?>">
                                <div class="badge badge-info btn-primary"><i class="fa fa-download"></i> Click Aqui para descargar</div>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo get_image('broken.png'); ?>" data-lightbox="Documento" title="<?php echo sprintf('No se encontro el documento.')?>">
                                <img src="<?php echo get_image('broken.png'); ?>" alt="<?php echo sprintf('Documento')?>" class="img-fluid img-thumbnail">
                            </a>
                            <p class="text-muted"><?php echo sprintf('El archivo %s no existe o está dañado.', $d->t->documento)?></p>
                        <?php endif; ?>
                    <?php else: ?>
                                No hay un documento disponible.        
                    <?php endif; ?>
                </div>
            </div>


            <!-- Contenido de la Tarea -->
            <div class="card shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#contenido_leccion" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="contenido_leccion">
                    <h6 class="m-0 font-weight-bold text-primary"> Contenido</h6>
                </a>
                <!-- Carda Content - Collapse -->
                <div class="collapse show" id="contenido_leccion">
                    <div class="card-body">
                        <?php echo nl2br($d->t->contenido); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once INCLUDES.'inc_footer.php'; ?>