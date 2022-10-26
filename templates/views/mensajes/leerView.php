<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <div class="col-xl 12">
        <!-- Collapsable Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#redactar_mensaje" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="redactar_mensaje">
                <h6 class="m-0 font-weight-bold text-primary">Detalles</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="redactar_mensaje">
                <div class="card-body">
                        <form enctype="multipart/form-data" action="mensajes/post_redactar" method="post">
                            <?php echo insert_inputs();?>
                            
                            <div class="form-group">
                                <div for="id_destinatario">De</div>
                                <input class="form-control" type="id_destinatario" value="<?php echo $d->remitente->nombre_completo?>" disabled>
                            </div>
                            
                            <div class="form-group">
                                <div for="asunto">Asunto</div>
                                <input type="text" class="form-control" id="asunto" name="asunto" value="<?php echo $d->mensaje->asunto?>" disabled>
                            </div>

                            <div class="form-group">
                                <div for="mensaje">Mensaje</div>
                                <div class="card">
                                    <?php echo nl2br($d->mensaje->mensaje); ?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                
                                <?php if (!empty($d->mensaje->documento)): ?>
                                    <div for="documento">Archivo adjunto</div>
                                    <?php if(is_file(UPLOADS.$d->mensaje->documento)): ?>
                                        <a href="<?php echo sprintf('assets/uploads/%s', $d->mensaje->documento)?>">
                                            <div class="badge badge-info btn-primary"><i class="fa fa-download"></i> Click Aqui para descargar</div>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo get_image('broken.png'); ?>" data-lightbox="Documento" title="<?php echo sprintf('No se encontro el documento.')?>">
                                            <img src="<?php echo get_image('broken.png'); ?>" alt="<?php echo sprintf('Documento')?>" class="img-fluid img-thumbnail">
                                        </a>
                                        <p class="text-muted"><?php echo sprintf('El archivo %s no existe o está dañado.', $d->mensaje->documento)?></p>
                                    <?php endif; ?>
                                <?php else: ?>
                                <?php endif; ?>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>