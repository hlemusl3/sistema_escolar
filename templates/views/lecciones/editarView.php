<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <div class="col-xl-6">
        <!-- Collapsable Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#editar_leccion" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="editar_leccion">
                <h6 class="m-0 font-weight-bold text-primary">Completa el formulario</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="editar_leccion">
                <div class="card-body">
                        <form action="lecciones/post_editar" method="post">
                            <?php echo insert_inputs();?>
                            <input type="hidden" name="id" value="<?php echo $d->l->id; ?>">

                            <div class="form-group">
                                <div for="titulo">Título de la lección</div>
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $d->l->titulo; ?>" required>
                            </div>

                            <div class="form-group">
                                <div for="video">Video de la lección</div>
                                <input type="text" class="form-control" id="video" name="video" placeholder="Ejemplo: https://youtu.be/dgW3bJgXXdk" value="<?php echo $d->l->video; ?>">
                            </div>

                            <div class="form-group">
                                <div for="contenido">Contenido</div>
                                <textarea name="contenido" id="contenido" cols="10" rows="5" class="form-control"><?php echo $d->l->contenido; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="status">Estado de la lección</label>
                                <select name="status" id="status" class="form-control">
                                    <?php foreach(get_estados_lecciones() as $e): ?>
                                        <?php echo sprintf('<option value="%s" %s>%s</option>', $e[0], $e[0] === $d->l->status ? 'selected' : null,$e[1]); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <div for="fecha_inicial">Fecha inicial</div>
                                <input type="datetime-local" class="form-control" id="fecha_inicial" name="fecha_inicial" value="<?php echo date('Y-m-d H:i:s', strtotime($d->l->fecha_inicial))?>" required>
                            </div>

                            <div class="form-group">
                                <div for="fecha_max">Fecha máxima</div>
                                <input type="datetime-local" class="form-control" id="fecha_max" name="fecha_max" value="<?php echo date('Y-m-d H:i:s', strtotime($d->l->fecha_disponible))?>" required>
                            </div>

                            <button class="btn btn-success" type="submit">Guardar cambios</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>