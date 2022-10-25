<?php
use \Verot\Upload\Upload;

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de perfil
 */
class perfilController extends Controller {
  private $id = null;
  function __construct()
  {
    // Validación de sesión de usuario
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }
    $this->id = get_user('id');
  }
  
  function index()
  {
    $usuario = usuarioModel::by_id($this->id);
    $data = 
    [
      'title' => sprintf('Información de %s', $usuario['nombre_completo']),
      'slug' => 'dashboard',
      'usuario' => $usuario
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  function ver($id)
  {
    View::render('ver');
  }

  function agregar()
  {
    View::render('agregar');
  }

  function post_agregar()
  {

  }

  function editar($id)
  {
    View::render('editar');
  }

  function post_editar()
  {
    try {
      if(!check_posted_data(['csrf','id'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones());
      }

      $id = clean($_POST["id"]);
      $foto = $_FILES["foto"];
      $n_foto = false;

      if(!$usuario = usuarioModel::by_id($id)){
        throw new Exception('No existe el usuario en la base de datos.');
      }

      $db_foto = $usuario['foto'];

      if ($foto['error'] !== 4) {
        $tmp = $foto['tmp_name'];
        $name = $foto['name'];
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        // Validar extensión del archivo
        if (!in_array($ext, ['jpg', 'png', 'jpeg', 'bmp'])) {
          throw new Exception('Selecciona un formato de imagen válido.');
        }

        $foo = new upload($foto);
        if (!$foo->uploaded) {
          throw new Exception('Hubo un problema al subir el archivo.');
        }

        //Nuevo nombre y nuevas medidas de la imagen
        $filename = generate_filename();
        $foo->file_new_name_body = $filename;
        $foo->image_resize = true;
        $foo->image_x = 800;
        $foo->image_ratio_y = true;

        $foo->process(UPLOADS);
        if (!$foo->processed) {
          throw new Exception('Hubo un error al guardar la imagen en el servidor.');
        }

        $data['foto'] = sprintf('%s.%s', $filename, $ext);
        $n_foto = true;
        //Actualizar en la base de datos
        if(!usuarioModel::update(usuarioModel::$t1, ['id' => $id], $data)){
          throw new Exception(get_notificaciones(3));
        }
        //Borrado del horario anterior en caso de actualización
        if ($db_foto !== null && $n_foto === true && is_file(UPLOADS.$db_foto)) {
          unlink(UPLOADS.$db_foto);
        }
        Flasher::new(sprintf('Perfil de <b>%s</b> actualizado con éxito.', get_user('nombre_completo')), 'success');
        Redirect::back();
      }
      Flasher::new('No hay cambios en la información', 'success');
      Redirect::back();

    } catch (PDOException $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }

  }

  function borrar($id)
  {
    // Proceso de borrado
  }
}