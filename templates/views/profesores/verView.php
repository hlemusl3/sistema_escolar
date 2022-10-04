<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
    <div class="col-xl-6">
        <!-- Collapsable Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="collapseCardExample">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?php echo sprintf('Profesor #%s', $d->p->numero); ?>
                    <div class="float-right">
                        <?php echo format_estado_usuario($d->p->status);?>
                    </div>
                </h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="collapseCardExample">
                <div class="card-body">
                        <form action="profesores/post_editar" method="post">
                            <?php echo insert_inputs();?>
                            <input type="hidden" name="id" value="<?php echo $d->p->id; ?>" required>
                            
                            <div class="form-group">
                                <div for="nombres">Nombre(s)</div>
                                <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo $d->p->nombres; ?>" required>
                            </div>

                            <div class="form-group">
                                <div for="apellidos">Apellido(s)</div>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $d->p->apellidos; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <div for="email">Correo electrónico</div>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $d->p->email; ?>" required>
                            </div>

                            <div class="form-group">
                                <div for="telefono">Teléfono</div>
                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $d->p->telefono; ?>">
                            </div>

                            <div class="form-group">
                                <div for="password">Contraseña</div>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>

                            <div class="form-group">
                                <div for="creado">Creado</div>
                                <input type="text" class="form-control" id="creado" name="creado" value="<?php echo format_date($d->p->creado); ?>" disabled>
                            </div>

                            <button class="btn btn-success" type="submit">Guardar cambios</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>