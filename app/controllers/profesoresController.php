<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de profesores
 */
class profesoresController extends Controller {
  function __construct()
  {
    //Validacion de sesión de usuario
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }
  }
  
  function index()
  {
    if(!is_admin(get_user_role())){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }

    $data = 
    [
      'title' => 'Todos los profesores',
      'slug' => 'profesores',
      'button' => ['url' => buildURL('profesores/agregar'), 'text' => '<i class="fas fa-plus"></i> Agregar profesores'],
      'profesores' => profesorModel::all_paginated()
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  function ver($id)
  {
    if(!$profesor = profesorModel::by_numero($id)){
      Flasher::new('No existe el profesor en la base de datos.', 'danger');
      Redirect::back();
    }

    $data =
    [
      'title' => sprintf('Profesor #%s', $profesor['numero']),
      'slug' => 'profesores',
      'button' => ['url' => 'profesores', 'text' => '<i class="fas fa-table"></i> Profesores'],
      'p' => $profesor
    ];

    View::render('ver', $data);
  }

  function agregar()
  {
    try {
      if(!check_get_data(['_t'], $_GET) || !Csrf::validate($_GET['_t'])){
        throw new Exception(get_notificaciones(0));
      }

      //validar rol
      if(!is_admin(get_user_role())){
        throw new Exception(get_notificaciones(1));
      }
      
      $numero = rand(111111, 999999);
      $data = 
      [
        'numero' => $numero,
        'nombres' => null,
        'apellidos' => null,
        'nombre_completo' => null,
        'email' => null,
        'password' => null,
        'telefono' => null,
        'hash' => generate_token(),
        'rol' => 'profesor',
        'status' => 'pendiente',
        'creado' => now()
      ];

      //Insertar a la base de datos
      if(!$id = profesorModel::add(profesorModel::$t1, $data)){
        throw new Exception(get_notificaciones(2));
      }

      Flasher::new(sprintf('Nuevo profesor <b>%s</b> agregada con éxito.', $numero), 'success');
      Redirect::to(sprintf('profesores/ver/%s', $numero));

    } catch (PDOException $e) { //Excepciones de errores por la db
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }

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