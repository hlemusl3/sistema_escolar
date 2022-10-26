<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <div class="col-xl-6">
        <!-- Collapsable Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#agregar_foro" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="agregar_foro">
                <h6 class="m-0 font-weight-bold text-primary">Completa el formulario</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="agregar_foro">
                <div class="card-body">
                        <form action="foros/post_editar" method="post">
                            <?php echo insert_inputs();?>
                            <input type="hidden" name="id_profesor" value="<?php echo $d->id_profesor; ?>">

                            <input type="hidden" name="id" value="<?php echo $d->f->id;?>">

                            <label for="id_materia">Materia</label>
                            <select name="id_materia" id="id_materia" class="form-control" disabled>
                                <?php echo sprintf('<option value="%s" %s>%s</option>', $d->f->id_materia, $d->f->id_materia, $d->m->nombre); ?>
                            </select>
                            <br>
                            <div class="form-group">
                                <div for="titulo">Título del foro</div>
                                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $d->f->titulo;?>" required>
                            </div>

                            <div class="form-group">
                                <div for="mensaje">Mensaje</div>
                                <textarea name="mensaje" id="mensaje" cols="10" rows="5" class="form-control"><?php echo $d->f->mensaje; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="status">Estado del foro</label>
                                <select name="status" id="status" class="form-control">
                                    <?php foreach(get_estados_tareas() as $e): ?>
                                        <?php echo sprintf('<option value="%s" %s>%s</option>', $e[0], $e[0] === $d->f->status ? 'selected' : null,$e[1]); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <div for="fecha_inicial">Fecha inicial</div>
                                <input type="datetime-local" class="form-control" id="fecha_inicial" name="fecha_inicial" value="<?php echo $d->f->fecha_inicial; ?>" required>
                            </div>

                            <div class="form-group">
                                <div for="fecha_max">Fecha máxima</div>
                                <input type="datetime-local" class="form-control" id="fecha_max" name="fecha_max" value="<?php echo $d->f->fecha_disponible; ?>" required>
                            </div>

                            <button class="btn btn-success" type="submit">Editar foro</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>