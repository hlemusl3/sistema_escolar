<?php
use \Verot\Upload\Upload;
/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de tareas
 */
class tareasController extends Controller {
  private $id = null;
  private $rol = null;

  function __construct()
  {
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }

    $this->id = get_user('id');
    $this->rol = get_user_role();
    
  }
  
  function index()
  {
    if(!is_admin(get_user_role())){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }

    $data = 
    [
      'title' => 'Todas las tareas',
      'slug' => 'tareas',
      'tareas' => tareaModel::all_paginated()
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

    $id_materia = $tarea['id_materia'];
 
    $grupos = grupoModel::grupos_materia_profesor($id_profesor, $id_materia);

    $data =
    [
      'title' => sprintf('Tarea: %s', $tarea['titulo']),
      'hide_title' => true,
      'slug' => is_admin($this->rol) ? 'tareas' : (is_profesor($this->rol) ? 'materias' : 'grupos'),
      'id_profesor' => $id_profesor,
      'id_tarea' => $tarea['id'],
      't' => $tarea,
      'g' => $grupos
    ];

    View::render('ver', $data);
  }

  function ver_admin($id)
  {
    if(!is_profesor(get_user_role())) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::to('dashboard');
    }

      //Validar que exista la lección
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
      'hide_title' => true,
      'slug' => 'tareas',
      'id_profesor' => $id_profesor,
      't' => $tarea
    ];

    View::render('ver_admin', $data);
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
      'slug' => 'materias',
      'button' => ['url' => 'materias/asignadas', 'text' => '<i class="fas fa-table"></i> Todas mis materias'],
      'id_profesor' => $id_profesor,
      'id_materia' => isset($_GET["id_materia"]) ? $_GET["id_materia"] : null,
      'materias_profesor' => materiaModel::materias_profesor($id_profesor)
    ];

    View::render('agregar', $data);
  }

  function post_agregar()
  {
    try {
      if(!check_posted_data(['csrf','titulo','instrucciones','enlace','id_materia','id_profesor','fecha_inicial','fecha_max','status'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones());
      }

      //Validar rol
      if(!is_profesor(get_user_role())){
        throw new Exception(get_notificaciones());
      }

      $titulo = clean($_POST["titulo"]);
      $instrucciones = clean($_POST["instrucciones"]);
      $enlace = clean($_POST["enlace"]);
      $documento = $_FILES["documento"];
      $id_materia = clean($_POST["id_materia"]);
      $id_profesor = clean($_POST["id_profesor"]);
      $fecha_inicial = clean($_POST["fecha_inicial"]);
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
        'status' => $status,
        'fecha_inicial' => $fecha_inicial,
        'fecha_disponible' => $fecha_max,
        'creado' => now()
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
        $n_documento = true;
      }

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
      'slug' => 'materias',
      'button' => ['url' => sprintf('grupos/materia/%s', $tarea['id_materia']), 'text' => '<i class="fas fa-undo"></i> Lecciones y Tarea'],
      'id_profesor' => $id_profesor,
      't' => $tarea
    ];

    View::render('editar', $data);
  }

  function post_editar()
  {
    try {
      if(!check_posted_data(['csrf','id','titulo','instrucciones','enlace','fecha_inicial','fecha_max','status'], $_POST) || !Csrf::validate($_POST['csrf'])){
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
      $documento = $_FILES["documento"];
      $n_documento = false;
      $fecha_inicial = clean($_POST["fecha_inicial"]);
      $fecha_max = clean($_POST["fecha_max"]);
      $status = clean($_POST["status"]);
      
      $db_documento = $tarea["documento"];    

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
        'status' => $status,
        'fecha_inicial' => $fecha_inicial,
        'fecha_disponible' => $fecha_max
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
        $n_documento = true;
      }

      //Actualizar registro
      if(!tareaModel::update(tareaModel::$t1, ['id' => $id], $data)){
        throw new Exception(get_notificaciones(3));
      }

      //Borrando el archivo anterior
      if ($db_documento !== null && $n_documento === true && is_file(UPLOADS.$db_documento)){
        unlink(UPLOADS.$db_documento);
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
    try {
      if(!check_get_data(['_t'], $_GET) || !Csrf::validate($_GET['_t'])){
        throw new Exception(get_notificaciones(0));
      }

      if(!is_profesor(get_user_role())) {
        throw new Exception(get_notificaciones());
      }
  
      //Validar que exista la lección
      if (!$tarea = tareaModel::by_id($id)) {
        throw new Exception(get_notificaciones());
      }

      $id_profesor = get_user('id');

      //Validar el id del profesor y del registro
      if ($tarea['id_profesor'] !== $id_profesor && !is_admin(get_user_role())) {
        throw new Exception(get_notificaciones());
      }

      //Validar si hay entregas de alumnos en la tarea
      if(!empty($entregas = entregaModel::by_id_tarea($id))){

        foreach ($entregas as $r) {
          if(!empty($r['documento']) && is_file(UPLOADS.$r['documento'])){
            unlink(UPLOADS.$r['documento']);
          }
        }
        entregaModel::remove(entregaModel::$t1, ['id_tarea' => $id]);
      }

      //Quitar el registro de la base de datos
      if(!tareaModel::remove(tareaModel::$t1, ['id' => $id], 1)){
        throw new Exception(get_notificaciones(4));
      }

      $documento = $tarea['documento'];

      if(!empty($documento) && is_file(UPLOADS.$documento)){
        unlink(UPLOADS.$documento);
      }

      Flasher::new(sprintf('Tarea titulada <b>%s</b> borrada con éxito.', $tarea['titulo']), 'success');
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