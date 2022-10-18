<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <div class="col-xl-6">
        <!-- Collapsable Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#editar_tarea" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="editar_tarea">
                <h6 class="m-0 font-weight-bold text-primary">Completa el formulario</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="editar_tarea">
                <div class="card-body">
                        <form enctype="multipart/form-data" action="tareas/post_editar" method="post">
                            <?php echo insert_inputs();?>
                            <input type="hidden" name="id" value="<?php echo $d->t->id; ?>">

                            <div class="form-group">
                                <div for="titulo">Título de la tarea *</div>
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $d->t->titulo; ?>" required>
                            </div>

                            <div class="form-group">
                                <div for="instrucciones">Instrucciones *</div>
                                <textarea name="instrucciones" id="instrucciones" cols="10" rows="5" class="form-control" required><?php echo $d->t->instrucciones; ?></textarea>
                            </div>

                            <div class="form-group">
                                <div for="enlace">Enlace</div>
                                <input type="text" class="form-control" id="enlace" name="enlace" value="<?php echo $d->t->enlace; ?>">
                            </div>

                            <div class="form-group">
                                <div for="documento">Documento</div>
                                <input type="file" class="form-control" id="documento" name="documento" accept="image/*, video/*, audio/*, .pdf, .docx, .xlsx, .doc, .xls">
                            </div>

                            <div class="form-group">
                                <label for="status">Estado de la tarea</label>
                                <select name="status" id="status" class="form-control">
                                    <?php foreach(get_estados_tareas() as $e): ?>
                                        <?php echo sprintf('<option value="%s" %s>%s</option>', $e[0], $e[0] === $d->t->status ? 'selected' : null,$e[1]); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <div for="fecha_max">Fecha máxima</div>
                                <input type="date" class="form-control" id="fecha_max" name="fecha_max" value="<?php echo date('Y-m-d', strtotime($d->t->fecha_disponible))?>" required>
                            </div>

                            <button class="btn btn-success" type="submit">Guardar cambios</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>