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
    $data = 
    [
      'title' => 'Calendario',
      'slug' => 'calendario'
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

  }

  function borrar($id)
  {
    // Proceso de borrado
  }
}