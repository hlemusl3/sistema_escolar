<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <div class="col-xl-6">
        <!-- Collapsable Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#agregar_tarea" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="agregar_tarea">
                <h6 class="m-0 font-weight-bold text-primary">Completa el formulario</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="agregar_tarea">
                <div class="card-body">
                        <form enctype="multipart/form-data" action="tareas/post_agregar" method="post">
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
                                <div for="titulo">Título de la tarea *</div>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>

                            <div class="form-group">
                                <div for="instrucciones">Instrucciones *</div>
                                <textarea name="instrucciones" id="instrucciones" cols="10" rows="5" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <div for="enlace">Enlace</div>
                                <input type="text" class="form-control" id="enlace" name="enlace">
                            </div>

                            <div class="form-group">
                                <div for="documento">Documento</div>
                                <input type="file" class="form-control" id="documento" name="documento" accept="image/*, video/*, audio/*, .pdf, .docx, .xlsx, .doc, .xls">
                            </div>

                            <div class="form-group">
                                <label for="status">Estado de la tarea</label>
                                <select name="status" id="status" class="form-control">
                                    <?php foreach(get_estados_tareas() as $e): ?>
                                        <?php echo sprintf('<option value="%s">%s</option>', $e[0], $e[1]); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <div for="fecha_max">Fecha máxima</div>
                                <input type="date" class="form-control" id="fecha_max" name="fecha_max" required>
                            </div>

                            <button class="btn btn-success" type="submit">Crear Tarea</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>