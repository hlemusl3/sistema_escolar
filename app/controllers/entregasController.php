<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de entregas
 */
class entregasController extends Controller {
  function __construct()
  {
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }
    
  }
  
  function index()
  {
    if(!is_profesor(get_user_role())){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();      
    }

    $data = 
    [
      'title'  => 'Entregas',
      'slug'   => 'grupos',
      'button' => ['url' => 'grupos/asignados', 'text' => '<i class="fas fa-undo"></i> Mis grupos']
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  //Ver entregas del grupo de una tarea.
  function ver($id_tarea, $id_grupo)
  {
    if(!is_profesor(get_user_role())){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();      
    }

    if (!$tarea = tareaModel::by_id($id_tarea)) {
      throw new Exception('No existe la tarea en la base de datos.');
    }

    if (!$grupo = grupoModel::by_id($id_grupo)) {
      throw new Exception('No existe el grupo en la base de datos.');
    }

    $entregas = entregaModel::entrega_tarea_grupos($id_tarea, $id_grupo);

    $data = 
    [
      'title' => sprintf('Entregas del grupo <b>%s</b> para la tarea <b>%s</b>', $grupo['nombre'], $tarea['titulo']),
      'slug'   => 'grupos',
      'button' => ['url' => sprintf('tareas/ver/%s', $tarea['id']), 'text' => '<i class="fas fa-undo"></i> Regresar'],
      't' => $tarea,
      'g' => $grupo,
      'entregas' => $entregas
    ];
 
    View::render('ver', $data);
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

  }

  function borrar($id)
  {
    // Proceso de borrado
  }

  function detalle($id)
  {
    if(!is_profesor(get_user_role())){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();      
    }

    $entrega = entregaModel::by_id($id);
    $id_tarea = $entrega['id_tarea'];
    $id_alumno = $entrega['id_alumno'];
    $alumno = usuarioModel::by_id($id_alumno);
    $tarea = tareaModel::by_id($id_tarea);
    $grupo = grupoModel::grupo_alumno($id_alumno);
    $id_grupo = $grupo['id_grupo'];    
    $data = 
    [
      'title' => sprintf('Entregas del alumno <b>%s</b> para la tarea <b>%s</b>', $alumno['nombre_completo'], $tarea['titulo']),
      'slug'   => 'grupos',
      'button' => ['url' => sprintf('entregas/ver/%s/%s', $id_tarea, $id_grupo), 'text' => '<i class="fas fa-undo"></i> Regresar'],
      'entrega' => $entrega,
      'alumno' => $alumno
    ];
    view::render('detalle', $data);
  }
}