<?php require_once INCLUDES.'inc_header.php'; ?>

  <div class="row">
    <div class="col-xl-6">

      <div class="card shadow mb-4">
    
        <a href="#admin_panel" class="d-block card-header py-3" data-toggle="collapse"
          role="button" aria-expanded="true" aria-controls="admin_panel">
          <h6 class="m-0 font-weight-bold text-primary">Reiniciar sistema</h6>
        </a>

        <div class="collapse show" id="admin_panel">
          <div class="card-body">
            <form id="reiniciar_sistema_form" method="post">
              <?php echo insert_inputs(); ?>
              <button class="btn btn-success" type="submit"><i class="fas fa-database fa-fw"></i> Reiniciar base de datos</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-12">

      <div class="card shadow mb-4">
    
        <a href="#admin" class="d-block card-header py-3" data-toggle="collapse"
          role="button" aria-expanded="true" aria-controls="admin">
          <h6 class="m-0 font-weight-bold text-primary">Usuarios Administradores</h6>
        </a>

        <div class="collapse show" id="admin">
          <div class="card-body">
          <div class="card-body">
                            <?php if(!empty($d->admins)): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th>Nombre completo</th>
                                                <th>Correo electrónico</th>
                                                <th>Status</th>
                                                <th width="10%">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($d->admins as $a): ?>
                                            <tr>
                                                <td><?php echo sprintf('<a href="admin/ver/%s">%s</a>', $a->id, $a->numero); ?></td>
                                                <td><?php echo empty($a->nombre_completo) ? '<span class="text-muted">Sin nombre</span>' : add_ellipsis($a->nombre_completo, 50); ?></td>
                                                <td><?php echo empty($a->email) ? '<span class="text-muted">Sin correo electrónico</span>' : $a->email; ?></td>
                                                <td><?php echo format_estado_usuario($a->status); ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                      <a href="<?php echo 'admin/ver/'.$a->id; ?>" class="btn btn-sm btn-success"><i class="fas fa-eye"></i></a>
                                                      <?php if($a->status === 'suspendido'): ?>
                                                        <a href="<?php echo 'admin/remover_suspension/'.$a->id; ?>">
                                                          <button class="btn btn-warning text-dark btn-sm" data-id="<?php echo $a->id; ?>"><i class="fas fa-undo"></i></button>
                                                        </a>
                                                      <?php else: ?>
                                                        <?php if($a->status === 'pendiente'): ?>
                                                            <button class="btn btn-danger btn-sm suspender_alumno" data-view="alumnos" data-id="<?php echo $a->id; ?>" disabled><i class="fas fa-ban"></i></button>
                                                        <?php else: ?>
                                                          <a href="<?php echo 'admin/suspender/'.$a->id; ?>">
                                                            <button class="btn btn-danger text-white btn-sm" data-id="<?php echo $a->id; ?>"><i class="fas fa-ban"></i></button>
                                                          </a>
                                                        <?php endif; ?>
                                                      <?php endif; ?>
                                                        <a href="<?php echo buildURL('admin/borrar/'.$a->id); ?>" class="btn btn-sm btn-danger confirmar"><i class="fas fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                            <div class="py-5 text-center">
                                <img src="<?php echo IMAGES.'undraw_empty.png' ?>" alt="No hay registro" style="width: 250px;">
                                <p class="text-muted">No hay registros en la base de datos</p>
                            </div>
                            <?php endif; ?>
                        </div>
          </div>
        </div>
      </div>
    </div>

  </div>

<?php require_once INCLUDES.'inc_footer.php'; ?>