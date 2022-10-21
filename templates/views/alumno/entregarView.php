<?php require_once INCLUDES.'inc_header.php'; ?>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Accordion -->
                <a href="#alumno_data" class="d-block card-header py-3" data-toggle="collapse"
                    role="button" aria-expanded="true" aria-controls="alumno_data">
                    <h6 class="m-0 font-weight-bold text-primary">Detalles de la entrega</h6>
                </a>
                <!-- Card Content - Collapse -->
                <div class="collapse show" id="alumno_data">
                    <div class="card-body">
                    <form enctype="multipart/form-data" action="alumno/post_entregar" method="post">
                            <?php echo insert_inputs();?>
                            <input type="hidden" name="id_tarea" value="<?php echo $d->id_tarea; ?>">
                            <input type="hidden" name="id_alumno" value="<?php echo $d->id_alumno; ?>">

                            <div class="form-group">
                                <div for="comentario">Comentario *</div>
                                <textarea name="comentario" id="comentario" cols="10" rows="5" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <div for="enlace">Enlace</div>
                                <input type="text" class="form-control" id="enlace" name="enlace">
                            </div>

                            <div class="form-group">
                                <div for="documento">Documento</div>
                                <input type="file" class="form-control" id="documento" name="documento" accept="image/*, video/*, audio/*, .pdf, .docx, .xlsx, .doc, .xls, .rar, .zip">
                            </div>

                            <button class="btn btn-success" type="submit">Realizar Entrega</button>
                        </form>
                    </div>
                </div>
            </div>            
        </div>
    </div>
<?php require_once INCLUDES.'inc_footer.php'; ?>