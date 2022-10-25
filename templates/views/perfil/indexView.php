<?php require_once INCLUDES.'inc_header.php'; ?>

  <div class="row">
    <div class="col-xl-6 col-md-6 col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Detalles del perfil</h6>    
        </div>
        <div class="card-body">
          <form action="perfil/post_editar" method="post" enctype="multipart/form-data">
            <?php echo insert_inputs();?>
              <input type="hidden" name="id" value="<?php echo $d->usuario->id; ?>">
              <div class="form-group">
                <?php if ($d->usuario->foto !== null ): ?>
                    <?php if (is_file(UPLOADS.$d->usuario->foto)): ?>
                        <a href="<?php echo UPLOADED.$d->usuario->foto; ?>" data-lightbox="foto" title="<?php echo sprintf('Fotografía de %s', $d->usuario->nombre_completo); ?>">
                            <img width="150" src="<?php echo UPLOADED.$d->usuario->foto; ?>" alt="<?php echo sprintf('Fotografía de %s', $d->usuario->nombre_completo); ?>" class="img-fluid img-thumbnail">
                        </a>
                    <?php else: ?>
                        <a href="<?php echo get_image('broken.png'); ?>" data-lightbox="foto" title="<?php echo sprintf('Fotografía del %s', $d->usuario->nombre_completo); ?>">
                            <img width="150" src="<?php echo get_image('broken.png'); ?>" alt="<?php echo sprintf('Fotografía de %s', $d->usuario->nombre_completo); ?>" class="img-fluid img-thumbnail">
                        </a>
                        <p class="text-muted"><?php echo sprintf('El archivo <b>%s</b> no existe o está dañado.', $d->usuario->foto); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                  <strong>
                    Este perfil aún no tiene fotografía.
                  </strong>
                <?php endif; ?>
                  
              </div>
              <div class="form-group">
                <div for="foto">Fotografía</div>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/png, image/jpeg, image/gif">                
              </div>
              <div class="form-group">
                <div for="nombre.s">Nombres</div>   
                <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo $d->usuario->nombres; ?>" disabled>    
              </div>
              <div class="form-group">
                <div for="apellidos">Apellidos</div>   
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $d->usuario->apellidos; ?>" disabled>    
              </div>
              <div class="form-group">
                <div for="email">Correo electrónico</div>   
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $d->usuario->email; ?>" disabled>    
              </div>
              <div class="form-group">
                <div for="telefono">Teléfono</div>   
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $d->usuario->telefono; ?>" disabled>    
              </div>
              <div class="form-group">
                <div for="actualizado">actualizado</div>   
                <input type="actualizado" class="form-control" id="actualizado" name="actualizado" value="<?php echo $d->usuario->actualizado; ?>" disabled>    
              </div>

              <button class="btn btn-success" type="submit">Guardar cambios</button>

          </form>
        </div>
      </div>        
    </div>
  </div>
<?php require_once INCLUDES.'inc_footer.php'; ?>