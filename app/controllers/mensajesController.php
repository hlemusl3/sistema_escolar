<?php
use \Verot\Upload\Upload;

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de mensajes
 */
class mensajesController extends Controller {
  private $id = null;

  function __construct()
  {
    // Validación de sesión de usuario, descomentar si requerida
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }
    $this->id = get_user('id');
  }
  
  function index()
  {
    $data = 
    [
      'title' => 'Todos mis mensajes',
      'slug' => 'mensajes',
      'button' => ['url' => 'mensajes/redactar', 'text' => '<i class="fas fa-plus"></i> Redactar nuevo mensaje'],
      'mensajes' => mensajeModel::recibidos_by_id_user($this->id),
      'enviados' => mensajeModel::enviados_by_id_user($this->id)
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  function redactar($id = null)
  {
    $data = 
    [
      'title' => 'Redactar nuevo mensaje',
      'slug' => 'mensajes',
      'button' => ['url' => 'javascript:history.back()', 'text' => '<i class="fas fa-undo"></i> Regresar'],
      'destinatario' => $id !== null ? $destinatario = usuarioModel::by_id($id) : $destinatarios = usuarioModel::all()
    ];
    
    // Descomentar vista si requerida
    View::render('redactar', $data);

  }

  function leer($id)
  {
    $mensaje = mensajeModel::by_id($id);
    $remitente = usuarioModel::by_id($mensaje['id_remitente']);

    $estado = ['estado' => 'leido'];

    mensajeModel::update(mensajeModel::$t1, ['id' => $id], $estado);

    $data = 
    [
      'title' => 'Detalles del mensaje',
      'slug' => 'mensajes',
      'button' => ['url' => 'javascript:history.back()', 'text' => '<i class="fas fa-undo"></i> Regresar'],
      'mensaje' => $mensaje,
      'remitente' => $remitente
    ];
    
    // Descomentar vista si requerida
    View::render('leer', $data);
  }

  function leer_enviado($id)
  {
    $mensaje = mensajeModel::by_id($id);
    $destinatario = usuarioModel::by_id($mensaje['id_destinatario']);

    $data = 
    [
      'title' => 'Detalles del mensaje',
      'slug' => 'mensajes',
      'button' => ['url' => 'javascript:history.back()', 'text' => '<i class="fas fa-undo"></i> Regresar'],
      'mensaje' => $mensaje,
      'destinatario' => $destinatario
    ];
    
    // Descomentar vista si requerida
    View::render('leer_enviado', $data);
  }

  function mover_a_papelera($id)
  {
    $estado = ['estado' => 'papelera'];

    mensajeModel::update(mensajeModel::$t1, ['id' => $id], $estado);

    Flasher::new('El mensaje se movió a la papelera', 'success');
    Redirect::to('mensajes');

  }

  function agregar()
  {
    View::render('agregar');
  }

  function post_redactar()
  {
    try {
      if (!check_posted_data(['csrf', 'id_destinatario', 'asunto', 'mensaje'], $_POST) || !Csrf::validate($_POST['csrf'])) {
        Flasher::new(get_notificaciones(), 'danger');
        Redirect::back();
      }

      $id_remitente = get_user('id');
      $id_destinatario = clean($_POST["id_destinatario"]);
      $asunto = clean($_POST["asunto"]);
      $mensaje = clean($_POST["mensaje"]);
      $documento = $_FILES["documento"];
      $fecha = now();

      $data = 
      [
        'id_remitente' => $id_remitente,
        'id_destinatario' => $id_destinatario,
        'asunto' => $asunto,
        'mensaje' => $mensaje,
        'estado' => 'noleido',
        'fecha' => now()
      ];

      //Validar si se está subiendo un documento
      if ($documento['error'] !== 4) {
        $tmp = $documento['tmp_name'];
        $name = $documento['name'];
        $ext = pathinfo($name, PATHINFO_EXTENSION);

        $foo = new upload($documento);
        if (!$foo->uploaded) {
          throw new Exception('Hubo un problema al subir el archivo.');
        }

        $filename = generate_filename();
        $foo->file_new_name_body = $filename;

        $foo->process(UPLOADS);
        if (!$foo->processed) {
          throw new Exception('Hubo un problema al guardar el archivo en el servidor.');
        }

        $data['documento'] = sprintf('%s.%s', $filename, $ext);
      }

      //Insertar en la base de datos
      if(!$id = mensajeModel::add(mensajeModel::$t1, $data)){
        throw new Exception();
      }

      Flasher::new('Mensaje enviado con éxito.', 'success');
      Redirect::to('mensajes');

    } catch (PDOException $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }

  function editar($id)
  {
    View::render('editar');
  }

  function post_editar()
  {

  }

  function borrar($id)
  {
    $mensaje = mensajeModel::by_id($id);
    $documento = $mensaje['documento'];

    if(!empty($documento)){
      //Borrar el documento
      if (is_file(UPLOADS.$documento)) {
        unlink(UPLOADS.$documento);
      }
    }
    mensajeModel::remove(mensajeModel::$t1, ['id' => $id]);
    Flasher::new('Mensaje borrado con éxito.', 'success');
    Redirect::to('mensajes');
  }
}