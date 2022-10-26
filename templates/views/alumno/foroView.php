<?php require_once INCLUDES.'inc_header.php'; ?>

<div class="row">
  <div class="col-12">
    <div class="card shadow mb-4">
      <div class="card-header font-weight-bold text-primary">
        <?php echo sprintf('Foro / <b>%s</b>', $d->f->titulo); ?>             
        <a href="javascript:history.back()" class="btn btn-primary btn-sm float-right"><i class="fas fa-undo"></i> Regresar atrás</a>
      </div>
      <div class="card-body">
       <textarea name="contenido" id="contenido" class="form-control" disabled><?php echo $d->f->mensaje; ?></textarea>
      </div>
        

    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <?php if(!empty($d->r)): ?>
      <?php foreach($d->r as $r): ?>
        <div class="card shadow mb-4">
          <div class="card-header font-weight-bold text-primary">
            <?php echo sprintf('Respuesta de %s', $r->usuario); ?>
            <p class="float-right">
              <?php echo $r->fecha;?>
            </p>
          </div>
          <div class="card-body">
            <textarea class="form-control" disabled><?php echo $r->mensaje; ?></textarea>
          </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
          <div class="card-body">
          No hay respuestas aun.
          </div>
      <?php endif; ?>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card shadow mb-4">
      <div class="card-header">
        </div>
        <div class="card-body">
        <form action="foros/post_responder" method="post">
          <?php echo insert_inputs();?>
          <label for="responder">Escribe una respuesta</label>
          <input type="hidden" name="id_usuario" value="<?php echo get_user('id'); ?>">
          <input type="hidden" name="id_foro" value="<?php echo $d->f->id; ?>">
          <textarea class="form-control" name="mensaje" id="form-control"></textarea>
          <br>
          <button class="btn btn-success" type="submit">Responder</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once INCLUDES.'inc_footer.php'; ?>