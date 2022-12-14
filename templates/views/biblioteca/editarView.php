<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <div class="col-xl-6">
        <!-- Collapsable Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#editar_recurso" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="editar_recurso">
                <h6 class="m-0 font-weight-bold text-primary">Completa el formulario</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="editar_recurso">
                <div class="card-body">
                        <form enctype="multipart/form-data" action="biblioteca/post_editar" method="post">
                            <?php echo insert_inputs();?>
                            <input type="hidden" name="id" value="<?php echo $d->r->id; ?>">

                            <div class="form-group">
                                <div for="titulo">Título del recurso *</div>
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $d->r->titulo; ?>" required>
                            </div>

                            <div class="form-group">
                                <div for="documento">Documento</div>
                                <input type="file" class="form-control" id="documento" name="documento" accept="image/*, video/*, audio/*, .pdf, .docx, .xlsx, .doc, .xls">
                                    <?php if (!empty($d->r->documento)): ?>
                                        <?php if(is_file(UPLOADS.$d->r->documento)): ?>
                                            <a href="<?php echo sprintf('assets/uploads/%s', $d->r->documento)?>">
                                                <div class="badge badge-info btn-primary"><i class="fa fa-download"></i> Click Aqui para descargar</div>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo get_image('broken.png'); ?>" data-lightbox="Documento" title="<?php echo sprintf('No se encontro el documento.')?>">
                                                <img src="<?php echo get_image('broken.png'); ?>" alt="<?php echo sprintf('Documento')?>" class="img-fluid img-thumbnail">
                                            </a>
                                            <p class="text-muted"><?php echo sprintf('El archivo %s no existe o está dañado.', $d->r->documento)?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                                No hay un documento disponible.        
                                    <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="status">Estado del recurso</label>
                                <select name="status" id="status" class="form-control">
                                    <?php foreach(get_estados_tareas() as $e): ?>
                                        <?php echo sprintf('<option value="%s" %s>%s</option>', $e[0], $e[0] === $d->r->status ? 'selected' : null,$e[1]); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button class="btn btn-success" type="submit">Guardar cambios</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>