<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de calendario
 */
class calendarioController extends Controller {
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
    
    if(is_admin(get_user_role())) {
      $data=['tareas' => tareaModel::all()]; 
    } elseif (is_profesor(get_user_role()) && !is_admin(get_user_role())) {
      $data=['tareas' => tareaModel::by_profesor(get_user('id'))];
    } elseif (is_alumno(get_user_role()) && !is_admin(get_user_role())) {
      $data=['tareas' => tareaModel::by_alumno(get_user('id'), true, null, null)];
    }
    
    $data = 
    [
      'title' => 'Calendario',
      'slug' => 'calendario',
      'tareas' => $tareas = (is_admin(get_user_role())) ? tareaModel::all() : $tareas = (is_profesor(get_user_role()) && !is_admin(get_user_role())) ? tareaModel::by_profesor(get_user('id')) : tareaModel::by_alumno(get_user('id'), true, null, null) 
    ]; 
    // Descomentar vista si requerida
    View::render('index', $data);
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
}