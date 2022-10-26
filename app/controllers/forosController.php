<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de foros
 */
class forosController extends Controller {
  private $id = null;
  private $rol = null;

  function __construct()
  {
    // Validación de sesión de usuario, descomentar si requerida
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }
    $this->id=get_user('id');
    $this->rol=get_user_role();
  }
  
  function index()
  {
    if(!is_admin($this->rol)){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }

    $data = 
    [
      'title' => 'Todos los foros',
      'slug' => 'foros',
      'foros' => foroModel::all()
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  function ver($id)
  {
    if(!is_profesor($this->rol)) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }

      //Validar que exista el foro
    if (!$foro = foroModel::by_id($id)) {
      Flasher::new('No existe el foro en la Base de Datos.', 'danger');
      Redirect::back();    
    }
   
    //Validar el id del profesor y del registro
    if ($foro['id_profesor'] !== $this->id && !is_admin($this->rol)) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();    
    }

    $data =
    [
      'title' => sprintf('Foro: %s', $foro['titulo']),
      'hide_title' => true,
      'slug' => is_admin($this->rol) ? 'foros' : (is_profesor($this->rol) ? 'materias' : 'grupos'),
      'id_profesor' => $this->id,
      'f' => $foro,
      'r' => foroModel::respuestas($id)
    ];

    View::render('ver', $data);
  }

  function agregar()
  {
    if(!is_profesor($this->rol)) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::to('dashboard');
    }

    $this->id = get_user('id');
    $data =
    [
      'title' => 'Agregar nuevo foro',
      'slug' => 'foros',
      'button' => ['url' => 'javascript:history.back()', 'text' => '<i class="fas fa-undo"></i> Regresar'],
      'id_profesor' => $this->id,
      'id_materia' => isset($_GET["id_materia"]) ? $_GET["id_materia"] : null,
      'materias_profesor' => materiaModel::materias_profesor($this->id)
    ];

    View::render('agregar', $data);
  }

  function post_agregar()
  {
    try {
      if(!check_posted_data(['csrf','titulo','mensaje','id_materia','id_profesor','fecha_inicial','fecha_max','status'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception($_POST["video"]);
      }

      //Validar rol
      if(!is_profesor($this->rol)){
        throw new Exception(get_notificaciones());
      }

      $titulo = clean($_POST["titulo"]);
      $mensaje = clean($_POST["mensaje"], true);
      $id_materia = clean($_POST["id_materia"]);
      $fecha_inicial = clean($_POST["fecha_inicial"]);
      $fecha_max = clean($_POST["fecha_max"]);
      $status = clean($_POST["status"]);

      //validar que el profesor exista
      if(!$profesor = profesorModel::by_id($this->id)) {
        throw new Exception('El profesor no existe en la base de datos.');
      }

      //Validar la materia
      if(!$materia = materiaModel::by_id($id_materia)) {
        throw new Exception('La materia no existe en la base de datos.');
      }

      $sql = 'SELECT mp.* FROM materias_profesores mp WHERE mp.id_materia = :id_materia AND mp.id_profesor = :id_profesor';
      if (!profesorModel::query($sql, ['id_materia' => $id_materia, 'id_profesor' => $this->id])) {
        throw new Exception(sprintf('El profesor no tiene asignada la materia <b>%s</b>', $materia["nombre"]));
      }

      //Validar el titulo del foro
      if (strlen($titulo) < 5) {
        throw new Exception('Ingresa un título mayor a 5 caracteres.');
      }

      //Foro a guardar
      $data =
      [
        'id_materia' => $id_materia,
        'id_profesor' => $this->id,
        'titulo' => $titulo,
        'mensaje' => $mensaje,
        'status' => $status,
        'fecha_inicial' => $fecha_inicial,
        'fecha_disponible' => $fecha_max
      ];

      //Insertar en la base de datos
      if(!$id = foroModel::add(foroModel::$t1, $data)){
        throw new Exception(get_notificaciones(2));
      }

      Flasher::new(sprintf('Nueva foro titulado <b>%s</b> agregado con éxito para la materia <b>%s</b>.', $titulo, $materia['nombre']), 'success');
      Redirect::to(sprintf('grupos/materia/%s', $id_materia));

    } catch (PDOException $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }

  function post_responder()
  {
      if(!check_posted_data(['csrf','id_foro','id_usuario','mensaje'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones());
      }

      $id_foro = clean($_POST["id_foro"]);
      $id_usuario = clean($_POST["id_usuario"]);
      $mensaje = clean($_POST["mensaje"]);
      $foro = foroModel::by_id($id_foro);
      $id_materia = $foro['id_materia'];
      
      //respuesta a guardar
      $data =
      [
        'id_foro' => $id_foro,
        'id_usuario' => $id_usuario,
        'mensaje' => $mensaje
      ];

      $id = foroModel::add(foroModel::$t2, $data);

      Flasher::new('Tu respuesta ha sido agregada con éxito', 'success');
      if(is_alumno(get_user_role()) && !is_admin(get_user_role())){
        Redirect::to(sprintf('alumno/foro/%s', $id_foro));
      }
      Redirect::to(sprintf('foros/ver/%s', $id_foro));
  }

  function editar($id)
  {
    if(!is_profesor($this->rol)) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::to('dashboard');
    }

    $foro = foroModel::by_id($id);

    $this->id = get_user('id');
    $id_materia = $foro['id_materia'];
    $materia = materiaModel::by_id($id_materia);

    $data =
    [
      'title' => sprintf('Editar foro: %s', $foro['titulo']),
      'slug' => 'foros',
      'button' => ['url' => 'javascript:history.back()', 'text' => '<i class="fas fa-undo"></i> Regresar'],
      'f' => $foro,
      'm' => $materia
    ];

    View::render('editar', $data);
  }

  function post_editar()
  {
    try {
      if(!check_posted_data(['csrf','id','titulo','mensaje','fecha_inicial','fecha_max','status'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones());
      }

      //Validar rol
      if(!is_profesor($this->rol)){
        throw new Exception(get_notificaciones());
      }

      $id = clean($_POST["id"]);
      $foro = foroModel::by_id($id);
      $id_materia = $foro['id_materia'];
      $titulo = clean($_POST["titulo"]);
      $mensaje = clean($_POST["mensaje"], true);
      $fecha_inicial = clean($_POST["fecha_inicial"]);
      $fecha_max = clean($_POST["fecha_max"]);
      $status = clean($_POST["status"]);

      //validar que el profesor exista
      if(!$profesor = profesorModel::by_id($this->id)) {
        throw new Exception('El profesor no existe en la base de datos.');
      }

      //Validar el titulo del foro
      if (strlen($titulo) < 5) {
        throw new Exception('Ingresa un título mayor a 5 caracteres.');
      }

      //Foro a guardar
      $data =
      [
        'id_profesor' => $this->id,
        'titulo' => $titulo,
        'mensaje' => $mensaje,
        'status' => $status,
        'fecha_inicial' => $fecha_inicial,
        'fecha_disponible' => $fecha_max
      ];

      //Actualizar en la base de datos
      if(!foroModel::update(foroModel::$t1, ['id' => $id], $data)){
        throw new Exception('Hubo un error al actualizar el registro.');
      }


      Flasher::new(sprintf('Foro actualizado con éxito', 'success'));
      Redirect::to(sprintf('grupos/materia/%s', $id_materia));

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
    try {
      if(!check_get_data(['_t'], $_GET) || !Csrf::validate($_GET['_t'])){
        throw new Exception(get_notificaciones(0));
      }

      if(!is_profesor(get_user_role())) {
        throw new Exception(get_notificaciones());
      }
  
      //Validar que exista el foro
      if (!$foro = foroModel::by_id($id)) {
        throw new Exception(get_notificaciones());
      }

      $id_profesor = get_user('id');

      //Validar el id del profesor y del registro
      if ($foro['id_profesor'] !== $id_profesor && !is_admin(get_user_role())) {
        throw new Exception(get_notificaciones());
      }

      //Validar si hay entregas de alumnos en la tarea
      if(!empty($respuestas = foroModel::by_id_foro($id))){

        foroModel::remove(foroModel::$t2, ['id_foro' => $id]);
      }

      //Quitar el registro de la base de datos
      if(!foroModel::remove(foroModel::$t1, ['id' => $id], 1)){
        throw new Exception(get_notificaciones(4));
      }

      Flasher::new(sprintf('Foro %s borrado con éxito.', $foro['titulo']), 'success');
      Redirect::back();

    } catch (PDOException $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }

  function misforos()
  {
    if(!is_profesor($this->rol)){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }

    $foros = foroModel::by_profesor($this->id);
    $data =
    [
      'title' => 'Mis foros',
      'slug' => 'foros',
      
      'foros' => $foros
    ];

    View::render('misforos',$data);
  }

}