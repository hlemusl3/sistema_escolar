<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de tareas
 */
class tareasController extends Controller {
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
      'msg'   => 'Bienvenido al controlador de "tareas", se ha creado con éxito si ves este mensaje.'
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
    if(!is_profesor(get_user_role())) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::to('dashboard');
    }

    $id_profesor = get_user('id');
    $data =
    [
      'title' => 'Agregar nueva tarea',
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
      if(!check_posted_data(['csrf','titulo','instrucciones','enlace','documento','id_materia','id_profesor','fecha_max','status'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones());
      }

      //Validar rol
      if(!is_profesor(get_user_role())){
        throw new Exception(get_notificaciones());
      }

      $titulo = clean($_POST["titulo"]);
      $instrucciones = clean($_POST["instrucciones"]);
      $enlace = clean($_POST["enlace"]);
      $documento = clean($_POST["documento"]);
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

      //Validar el titulo de la tarea
      if (strlen($titulo) < 5) {
        throw new Exception('Ingresa un título mayor a 5 caracteres.');
      }

      //Validar el enlace
      if (!filter_var($enlace, FILTER_VALIDATE_URL) && !empty($enlace)) {
        throw new Exception('Ingresa un enlace (URL) válido.');
      }

      //Tarea a Guardar
      $data =
      [
        'id_materia' => $id_materia,
        'id_profesor' => $id_profesor,
        'titulo' => $titulo,
        'instrucciones' => $instrucciones,
        'enlace' => $enlace,
        'documento' => $documento,
        'status' => $status,
        'fecha_disponible' => $fecha_max,
        'creado' => now()
      ];
      
      //Insertar en la base de datos
      if(!$id = tareaModel::add(tareaModel::$t1, $data)){
        throw new Exception();
      }

      Flasher::new(sprintf('Nueva tarea titulada <b>%s</b> agregada con éxito para la materia <b>%s</b>.', $titulo, $materia['nombre']), 'success');
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

     //Validar que exista la tarea
    if (!$tarea = tareaModel::by_id($id)) {
    Flasher::new('No existe la tarea en la Base de Datos.', 'danger');
    Redirect::back();    
    }

    $id_profesor = get_user('id');

    //Validar el id del profesor y del registro
    if ($tarea['id_profesor'] !== $id_profesor && !is_admin(get_user_role())) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();    
    }

    $data =
    [
      'title' => sprintf('Tarea: %s', $tarea['titulo']),
      'slug' => 'grupos',
      'button' => ['url' => sprintf('grupos/materia/%s', $tarea['id_materia']), 'text' => '<i class="fas fa-undo"></i> Lecciones y Tarea'],
      'id_profesor' => $id_profesor,
      't' => $tarea
    ];

    View::render('editar', $data);
  }

  function post_editar()
  {
    try {
      if(!check_posted_data(['csrf','id','titulo','instrucciones','enlace','documento','fecha_max','status'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones());
      }

      //Validar rol
      if(!is_profesor(get_user_role())){
        throw new Exception(get_notificaciones());
      }

      //validar que exista la tarea
      $id = clean($_POST["id"]);
      if(!$tarea = tareaModel::by_id($id)) {
        throw new Exception('No existe la tarea en la base de datos.');
      }

      $id_profesor = get_user('id');

      //Validar el id del profesor y del registro
      if ($tarea['id_profesor'] !== $id_profesor && !is_admin(get_user_role())) {
        throw new Exception(get_notificaciones());
      }


      $titulo = clean($_POST["titulo"]);
      $instrucciones = clean($_POST["instrucciones"]);
      $enlace = clean($_POST["enlace"]);
      $documento = clean($_POST["documento"]);
      $fecha_max = clean($_POST["fecha_max"]);
      $status = clean($_POST["status"]);

      //Validar el titulo de la tarea
      if (strlen($titulo) < 5) {
        throw new Exception('Ingresa un título mayor a 5 caracteres.');
      }

      //Validar el enlace
      if (!filter_var($enlace, FILTER_VALIDATE_URL) && !empty($enlace)) {
        throw new Exception('Ingresa un enlace (URL) válido.');
      }

      //Tarea a Guardar
      $data =
      [
        'titulo' => $titulo,
        'instrucciones' => $instrucciones,
        'enlace' => $enlace,
        'documento' => $documento,
        'status' => $status,
        'fecha_disponible' => $fecha_max
      ];
      
      //Actualizar registro
      if(!tareaModel::update(tareaModel::$t1, ['id' => $id], $data)){
        throw new Exception(get_notificaciones(3));
      }

      Flasher::new(sprintf('Tarea titulada <b>%s</b> actualizada con éxito.', $titulo), 'success');
      Redirect::to(sprintf('grupos/materia/%s', $tarea['id_materia']));

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