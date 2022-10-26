<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
  <div id="accordion" style="width: 100%">
    <!-- Recibidos -->
    <div class="card">
      <div class="card-header" id="headingOne">
        <h5 class="mb-0">
          <button class="btn btn-link " data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <h6 class="m-0 font-weight-bold text-primary">Recibidos</h6>
          </button>
        </h5>
      </div>

      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
        <div class="card-body">
          <?php if(!empty($d->mensajes)): ?>
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="35%">De</th>
                    <th width="35%">Asunto</th>
                    <th width="20%">Recibido el</th>
                    <th width="10%">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($d->mensajes as $m): ?>
                    <?php if($m->estado !== 'papelera'): ?>
                      <tr>
                        <td>
                          <?php if($m->estado === 'noleido'): ?>
                            <a style="text-decoration: none" href="<?php echo sprintf("mensajes/leer/%s", $m->id)?> "><i class="fas fa-envelope text-primary"></i> <?php echo $m->remitente ;?></a>
                          <?php elseif($m->estado === 'leido'): ?>
                            <a style="text-decoration: none" href="<?php echo sprintf("mensajes/leer/%s", $m->id)?> "><i class="fas fa-envelope-open-text text-primary"></i> <?php echo $m->remitente ;?></a>
                          <?php endif; ?>
                        </td>
                        <td>
                          <?php echo $m->asunto; ?>
                        </td>
                        <td>
                          <?php echo $m->fecha?>
                        </td>
                        <td>
                          <a href="<?php echo sprintf('mensajes/mover_a_papelera/'.$m->id); ?>" class="btn btn-sm btn-danger "><i class="fas fa-trash"></i></a>
                        </td>
                      </tr>                          
                    <?php endif; ?>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else:?>
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No hay mensajes</th>
                  </tr>
                </thead>
                <tbody>
                  <td>Tu bandeja de entrada está vacía.</td>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Enviados -->
    <div class="card">
      <div class="card-header" id="headingTwo">
        <h5 class="mb-0">
          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          <h6 class="m-0 font-weight-bold text-primary">Enviados</h6>
          </button>
        </h5>
      </div>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
        <div class="card-body">
        <?php if(!empty($d->enviados)): ?>
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="40%">Para</th>
                    <th width="40%">Asunto</th>
                    <th width="20%">Enviado el</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($d->enviados as $m): ?>
                    <tr>
                      <td>
                          <?php if($m->estado === 'noleido'): ?>
                            <a style="text-decoration: none" href="<?php echo sprintf("mensajes/leer_enviado/%s", $m->id)?> "><i class="fas fa-envelope text-primary"></i> <?php echo $m->destinatario ;?></a>
                          <?php else: ?>
                            <a style="text-decoration: none" href="<?php echo sprintf("mensajes/leer_enviado/%s", $m->id)?> "><i class="fas fa-envelope-open-text text-primary"></i> <?php echo $m->destinatario ;?></a>
                          <?php endif; ?>
                      </td>
                      <td>
                        <?php echo $m->asunto; ?>
                      </td>
                      <td>
                        <?php echo $m->fecha?>
                      </td>
                    </tr>                          
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else:?>
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No hay mensajes</th>
                  </tr>
                </thead>
                <tbody>
                  <td>Tu bandeja de salida está vacía.</td>
                </tbody>
              </table>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>

    <!-- Papelera -->
    <div class="card">
      <div class="card-header" id="headingThree">
        <h5 class="mb-0">
          <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          <h6 class="m-0 font-weight-bold text-primary">Papelera</h6>
          </button>
        </h5>
      </div>
      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
        <div class="card-body">
        <?php if(!empty($d->mensajes)): ?>
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th width="35%">De</th>
                    <th width="35%">Asunto</th>
                    <th width="20%">Enviado el</th>
                    <th width="10%">Acción</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($d->mensajes as $m): ?>
                    <?php if($m->estado == 'papelera'): ?>
                    <tr>
                      <td>
                        <?php if($m->estado === 'noleido'): ?>
                          <a style="text-decoration: none" href="<?php echo sprintf("mensajes/leer/%s", $m->id)?> "><i class="fas fa-envelope text-primary"></i> <?php echo $m->remitente ;?></a>
                        <?php else: ?>
                          <a style="text-decoration: none" href="<?php echo sprintf("mensajes/leer/%s", $m->id)?> "><i class="fas fa-envelope-open-text text-primary"></i> <?php echo $m->remitente ;?></a>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php echo $m->asunto; ?>
                      </td>
                      <td>
                        <?php echo $m->fecha?>
                      </td>
                      <td>
                          <a href="<?php echo sprintf('mensajes/borrar/'.$m->id); ?>" class="btn btn-sm btn-danger "><i class="fas fa-trash"></i></a>
                      </td>
                    </tr>                          
                    <?php endif;?>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else:?>
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No hay mensajes</th>
                  </tr>
                </thead>
                <tbody>
                  <td>Tu papelera está vacía.</td>
                </tbody>
              </table>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>