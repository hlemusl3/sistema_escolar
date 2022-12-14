<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <div class="col-xl-6">
        <!-- Collapsable Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#agregar_recurso" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="agregar_recurso">
                <h6 class="m-0 font-weight-bold text-primary">Completa el formulario</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="agregar_recurso">
                <div class="card-body">
                        <form enctype="multipart/form-data" action="biblioteca/post_agregar" method="post">
                            <?php echo insert_inputs();?>
                            <input type="hidden" name="id_profesor" value="<?php echo $d->id_profesor; ?>">

                            <?php if(!empty($d->materias_profesor)): ?>
                                <div class="form-group">
                                    <label for="id_materia">Materia *</label>
                                    <select name="id_materia" id="id_materia" class="form-control">
                                        <?php foreach ($d->materias_profesor as $m): ?>
                                            <?php echo sprintf('<option value="%s" %s>%s</option>', $m->id, $d->id_materia == $m->id ? 'selected' : null, $m->nombre); ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php else: ?>
                                <div class="form-group">
                                    <label for="id_materia">Materia *</label>
                                    <div class="alert alert-danger">No hay materias disponibles.</div>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <div for="titulo">T??tulo del recurso *</div>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>

                            <div class="form-group">
                                <div for="documento">Documento *</div>
                                <input type="file" class="form-control" id="documento" name="documento" accept="image/*, video/*, audio/*, .pdf, .docx, .xlsx, .doc, .xls, .rar, .zip, .pptx, .ppt" required>
                            </div>

                            <div class="form-group">
                                <label for="status">Estado del documento</label>
                                <select name="status" id="status" class="form-control">
                                    <?php foreach(get_estados_tareas() as $e): ?>
                                        <?php echo sprintf('<option value="%s">%s</option>', $e[0], $e[1]); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button class="btn btn-success" type="submit">Agregar Recurso</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>