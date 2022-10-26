<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <div class="col-xl 12">
        <!-- Collapsable Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#redactar_mensaje" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="redactar_mensaje">
                <h6 class="m-0 font-weight-bold text-primary">Nuevo Mensaje</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="redactar_mensaje">
                <div class="card-body">
                        <form enctype="multipart/form-data" action="mensajes/post_redactar" method="post">
                            <?php echo insert_inputs();?>
                            
                            <div class="form-group">
                                <div for="id_destinatario">Destinatario</div>
                                <select style="height= 50px" name="id_destinatario" id="id_destinatario" class="form-control" required>
                                    <?php foreach($d->destinatario as $d): ?>
                                        <?php echo sprintf('<option value="%s" %s>%s</option>', $d->id, $d->id, $d->nombre_completo); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <div for="asunto">Asunto</div>
                                <input type="text" class="form-control" id="asunto" name="asunto" required>
                            </div>

                            <div class="form-group">
                                <div for="mensaje">Mensaje</div>
                                <textarea name="mensaje" id="mensaje" cols="10" rows="5" class="form-control" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <div for="documento">Archivo adjunto</div>
                                <input type="file" class="form-control" id="documento" name="documento" accept="image/*, video/*, audio/*, .pdf, .docx, .xlsx, .doc, .xls, .rar, .zip">
                            </div>
                            <button class="btn btn-success" type="submit">Enviar</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>