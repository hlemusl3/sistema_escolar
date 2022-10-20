<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de lecciones
 */
class leccionesController extends Controller {
  function __construct()
  {
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }
  }
  
  function index()
  {

    $data = 
    [
      'title' => 'Reemplazar título',
      'msg'   => 'Bienvenido al controlador de "lecciones", se ha creado con éxito si ves este mensaje.'
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  function ver($id)
  {
    if(!is_profesor(get_user_role())) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::to('dashboard');
    }

      //Validar que exista la lección
    if (!$leccion = leccionModel::by_id($id)) {
      Flasher::new('No existe la lección en la Base de Datos.', 'danger');
      Redirect::back();    
    }

    $id_profesor = get_user('id');
    
    //Validar el id del profesor y del registro
    if ($leccion['id_profesor'] !== $id_profesor && !is_admin(get_user_role())) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();    
    }

    $data =
    [
      'title' => sprintf('Lección: %s', $leccion['titulo']),
      'hide_title' => true,
      'slug' => 'grupos',
      'id_profesor' => $id_profesor,
      'l' => $leccion
    ];

    View::render('ver', $data);
  }

  function agregar()
  {
    if(!is_profesor(get_user_role())) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::to('dashboard');
    }

    $id_profesor = get_user('id');
    $data =
    [
      'title' => 'Agregar nueva lección',
      'slug' => 'grupos',
      'button' => ['url' => 'grupos/asignados', 'text' => '<i class="fas fa-table"></i> Todos mis grupos'],
      'id_profesor' => $id_profesor,
      'id_materia' => isset($_GET["id_materia"]) ? $_GET["id_materia"] : null,
      'materias_profesor' => materiaModel::materias_profesor($id_profesor)
    ];

    View::render('agregar', $data);
  }

  function post_agregar()
  {
    try {
      if(!check_posted_data(['csrf','titulo','video','contenido','id_materia','id_profesor','fecha_max','status'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception($_POST["video"]);
      }

      //Validar rol
      if(!is_profesor(get_user_role())){
        throw new Exception(get_notificaciones());
      }

      $titulo = clean($_POST["titulo"]);
      $video = clean($_POST["video"]);
      $contenido = clean($_POST["contenido"], true);
      $id_materia = clean($_POST["id_materia"]);
      $id_profesor = clean($_POST["id_profesor"]);
      $fecha_max = clean($_POST["fecha_max"]);
      $status = clean($_POST["status"]);

      //validar que el profesor exista
      if(!$profesor = profesorModel::by_id($id_profesor)) {
        throw new Exception('El profesor de la lección no existe en la base de datos.');
      }

      //Validar la materia
      if(!$materia = materiaModel::by_id($id_materia)) {
        throw new Exception('La materia no existe en la base de datos.');
      }

      $sql = 'SELECT mp.* FROM materias_profesores mp WHERE mp.id_materia = :id_materia AND mp.id_profesor = :id_profesor';
      if (!profesorModel::query($sql, ['id_materia' => $id_materia, 'id_profesor' => $id_profesor])) {
        throw new Exception(sprintf('El profesor no tiene asignada la materia <b>%s</b>', $materia["nombre"]));
      }

      //Validar el titulo de la leccion
      if (strlen($titulo) < 5) {
        throw new Exception('Ingresa un título mayor a 5 caracteres.');
      }

      //Validar el url del video
      if (!filter_var($video, FILTER_VALIDATE_URL) && !empty($video)) {
        throw new Exception('Ingresa una URL de video válida.');
      }

      //Lección a guardar
      $data =
      [
        'id_materia' => $id_materia,
        'id_profesor' => $id_profesor,
        'titulo' => $titulo,
        'video' => $video,
        'contenido' => $contenido,
        'status' => $status,
        'fecha_disponible' => $fecha_max,
        'creado' => now()
      ];

      //Insertar en la base de datos
      if(!$id = leccionModel::add(leccionModel::$t1, $data)){
        throw new Exception(get_notificaciones(2));
      }

      Flasher::new(sprintf('Nueva lección titulada <b>%s</b> agregada con éxito para la materia <b>%s</b>.', $titulo, $materia['nombre']), 'success');
      Redirect::to(sprintf('grupos/materia/%s', $id_materia));

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
    if(!is_profesor(get_user_role())) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::to('dashboard');
    }

    //Validar que exista la lección
  if (!$leccion = leccionModel::by_id($id)) {
    Flasher::new('No existe la lección en la Base de Datos.', 'danger');
    Redirect::back();    
  }
  $id_profesor = get_user('id');
  //Validar el id del profesor y del registro
  if ($leccion['id_profesor'] !== $id_profesor && !is_admin(get_user_role())) {
    Flasher::new(get_notificaciones(), 'danger');
    Redirect::back();    
  }
    $data =
    [
      'title' => sprintf('Lección: %s', $leccion['titulo']),
      'slug' => 'grupos',
      'button' => ['url' => sprintf('grupos/materia/%s', $leccion['id_materia']), 'text' => '<i class="fas fa-undo"></i> Lecciones y Tareas'],
      'id_profesor' => $id_profesor,
      'l' => $leccion
    ];
 
    View::render('editar', $data);
  }

  function post_editar()
  {
    try {
      if (!check_posted_data(['csrf','id','titulo','video','contenido','fecha_max','status'],$_POST) || !Csrf::validate($_POST['csrf'])) {
        throw new Exception(get_notificaciones());
      }

      //Validar Rol
      if(!is_profesor(get_user_role())) {
        Flasher::new(get_notificaciones(), 'danger');
        Redirect::to('dashboard');
      }
      
      //validar que exista la leccion
      $id = clean($_POST["id"]);
      if(!$leccion = leccionModel::by_id($id)) {
        throw new Exception('No existe la lección en la base de datos.');
      }

      $id_profesor = get_user('id');

      //Validar el id del profesor y del registro
      if ($leccion['id_profesor'] !== $id_profesor && !is_admin(get_user_role())) {
        throw new Exception(get_notificaciones());
      }

      $titulo = clean($_POST["titulo"]);
      $video = clean($_POST["video"]);
      $contenido = clean($_POST["contenido"], true);
      $fecha_max = clean($_POST["fecha_max"]);
      $status = clean($_POST["status"]);

      //Validar el nombre de la lección
      if (strlen($titulo) < 5) {
        throw new Exception('Ingresa un título mayor a 5 caracteres.');
      }

      //Validar el url del video
      if (!filter_var($video, FILTER_VALIDATE_URL) && !empty($video)) {
        throw new Exception('Ingresa una URL de video válida.');
      }

      //Lección a guardar
      $data =
      [
        'titulo' => $titulo,
        'video' => $video,
        'contenido' => $contenido,
        'status' => $status,
        'fecha_disponible' => $fecha_max,
      ];

      //Actualizar registro
      if(!leccionModel::update(leccionModel::$t1, ['id' => $id], $data)){
        throw new Exception(get_notificaciones(3));
      }

      Flasher::new(sprintf('Lección titulada <b>%s</b> actualizada con éxito.', $titulo), 'success');
      Redirect::to(sprintf('grupos/materia/%s', $leccion['id_materia']));
 
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
  
      //Validar que exista la lección
      if (!$leccion = leccionModel::by_id($id)) {
        throw new Exception(get_notificaciones());
      }

      $id_profesor = get_user('id');

      //Validar el id del profesor y del registro
      if ($leccion['id_profesor'] !== $id_profesor && !is_admin(get_user_role())) {
        throw new Exception(get_notificaciones());
      }
      
      //Quitar el registro de la base de datos
      if(!leccionModel::remove(leccionModel::$t1, ['id' => $id], 1)){
        throw new Exception(get_notificaciones(4));
      }

      Flasher::new(sprintf('Lección titulada <b>%s</b> borrada con éxito.', $leccion['titulo']), 'success');
      Redirect::back();

    } catch (PDOException $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }
}