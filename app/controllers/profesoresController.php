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
      'button' => ['url' => buildURL('profesores/agregar'), 'text' => '<i class="fas fa-plus"></i> Agregar profesor'],
      'profesores' => profesorModel::all_paginated()
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  function ver($numero)
  {
    if(!$profesor = profesorModel::by_numero($numero)){
      Flasher::new('No existe el profesor en la base de datos.', 'danger');
      Redirect::back();
    }

    $data =
    [
      'title' => sprintf('Profesor %s', $profesor['nombre_completo']),
      'slug' => 'profesores',
      'button' => ['url' => 'profesores', 'text' => '<i class="fas fa-table"></i> Todos los Profesores'],
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

      Flasher::new(sprintf('Nuevo profesor <b>%s</b> agregado con éxito.', $numero), 'success');
      Redirect::to(sprintf('profesores/ver/%s', $numero));

    } catch (PDOException $e) { //Excepciones de errores por la db
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }

  }

  function post_editar()
  {
    try {
      if(!check_posted_data(['csrf', 'id', 'nombres', 'apellidos', 'email', 'telefono', 'password'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones(0));
      }

      //validar rol
      if(!is_admin(get_user_role())){
        throw new Exception(get_notificaciones(1));
      }
      
      $id = clean($_POST["id"]);

      if(!$profesor = profesorModel::by_id($id)) {
        throw new Exception('No existe el profesor en la base de datos.');
      }

      $nombres = clean($_POST["nombres"]);
      $apellidos = clean($_POST["apellidos"]);
      $email = clean($_POST["email"]);
      $telefono = clean($_POST["telefono"]);
      $password = clean($_POST["password"]);

      //Validar que el correo sea válido
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        throw new Exception('Ingresa un correo electrónico válido.');
      }

      //Validar que el correo no lo tenga otro usuario
      if(usuarioModel::by_email($email)) {
      throw new Exception('El correo electrónico ya está en uso.');
      }
      
      $data = 
      [
        'nombres' => $nombres,
        'apellidos' => $apellidos,
        'nombre_completo' => sprintf('%s %s', $nombres, $apellidos),
        'email' => $email,
        'telefono' => $telefono
      ];

      //En caso de que se cambie el correo electrónico
      if($profesor['email'] !== $email && !in_array($profesor['status'], ['pendiente', 'suspendido'])){
        $data['status'] = 'pendiente';
      }

      //En caso de que se cambie la contraseña
      if(!empty($password) && !password_verify($password.AUTH_SALT, $profesor['password'])){
        $data['password'] = password_hash($password.AUTH_SALT, PASSWORD_BCRYPT);
      }

      //Insertar a la base de datos
      if(!profesorModel::update(profesorModel::$t1, ['id' => $id], $data)){
        throw new Exception(get_notificaciones(3));
      }

      //Volver a cargar la información del profesor
      $profesor = profesorModel::by_id($id);

      Flasher::new(sprintf('Profesor <b>%s</b> actualizado con éxito.', $profesor['nombre_completo']), 'success');
      Redirect::back();

    } catch (PDOException $e) { //Excepciones de errores por la db
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

      //validar rol
      if(!is_admin(get_user_role())){
        throw new Exception(get_notificaciones(1));
      }

      // Exista el profesor
         if(!$profesor = profesorModel::by_id($id)) {
        throw new Exception('No existe el profesor en la base de datos.');
      }

      //Si no existe registro en el profesor, borrar solo el profesor.
        if(!$materia = materiaModel::materias_profesor($id)) {
            if (profesorModel::eliminar_solo_profesor($profesor['id']) === false ) {
              throw new Exception('No se pudo borrar');
            }
              Flasher::new(sprintf('Profesor <b>%s</b> borrado con éxito.', $profesor['nombre_completo']), 'success');
              Redirect::to('profesores');
      
        }

      //Borramos el registro y sus conexiones
      if (profesorModel::eliminar($profesor['id']) === false ) {
        throw new Exception(get_notificaciones(4));
      }

      Flasher::new(sprintf('Profesor <b>%s</b> borrado con éxito.', $profesor['nombre_completo']), 'success');
      Redirect::to('profesores');

    } catch (PDOException $e) { //Excepciones de errores por la db
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }

  }

}