<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de alumnos
 */
class alumnosController extends Controller {
  function __construct()
  {
    //Validación de la sesión de usuario
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
      'title' => 'Todos los alumnos',
      'slug' => 'alumnos',
      'button' => ['url' => 'alumnos/agregar', 'text' => '<i class="fas fa-plus"></i> Agregar alumno'],
      'alumnos' => alumnoModel::all_paginated()
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  function ver($id)
  {
    if(!$alumno = alumnoModel::by_id($id)){
      Flasher::new('No existe el alumno en la base de datos.', 'danger');
      Redirect::back();
    }

    $data =
    [
      'title' => sprintf('Alumno(a) %s', $alumno['nombre_completo']),
      'slug' => 'alumnos',
      'button' => ['url' => 'alumnos', 'text' => '<i class="fas fa-table"></i> Todos los alumnos'],
      'grupos' => grupoModel::all(),
      'a' => $alumno
    ];

    View::render('ver', $data);
  }

  function agregar()
  {
    $data = 
    [
      'title' => 'Agregar alumno',
      'slug' => 'alumnos',
      'button' => ['url' => 'alumnos', 'text' => '<i class="fas fa-table"></i> Todos los alumnos'],
      'grupos' => grupoModel::all()
    ];


    View::render('agregar', $data);
  }

  function post_agregar()
  {
    try {
      if(!check_posted_data(['csrf', 'nombres', 'apellidos', 'email', 'telefono', 'password', 'conf_password', 'id_grupo'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones(0));
      }

      //validar rol
      if(!is_admin(get_user_role())){
        throw new Exception(get_notificaciones(1));
      }
      
      $nombres = clean($_POST["nombres"]);
      $apellidos = clean($_POST["apellidos"]);
      $email = clean($_POST["email"]);
      $telefono = clean($_POST["telefono"]);
      $password = clean($_POST["password"]);
      $conf_password = clean($_POST["conf_password"]);
      $id_grupo = clean($_POST["id_grupo"]);

      //Validar que el correo sea válido
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        throw new Exception('Ingresa un correo electrónico válido.');
      }

      //Validar el nombre del usuario
      if(strlen($nombres) < 3) {
        throw new Exception('Ingresa un nombre válido.');
      }

      //Validar el apellido del usuario
      if(strlen($apellidos) < 3 ) {
        throw new Exception('Ingresa un apellido válido.');
      }

      //Validar longitud de la contraseña
      if(strlen($password) < 6 ) {
        throw new Exception('Ingresa una contraseña mayor a 6 caracteres.');
      }
      
      // Validar que las contraseñas sean iguales
      if($password !== $conf_password) {
        throw new Exception('Las contraseñas no son iguales.');
      }

      //Validar que el grupo exista
      if($id_grupo === '' || !grupoModel::by_id($id_grupo)) {
        throw new Exception('Selecciona un grupo válido.');
      }

      //Validar que el correo no lo tenga otro usuario
      if(usuarioModel::by_email($email)) {
        throw new Exception('El correo electrónico ya está en uso.');
      }

      $data = 
      [
        'numero' => rand(111111,999999),
        'nombres' => $nombres,
        'apellidos' => $apellidos,
        'nombre_completo' => sprintf('%s %s', $nombres, $apellidos),
        'email' => $email,
        'telefono' => $telefono,
        'password' => password_hash($password.AUTH_SALT, PASSWORD_BCRYPT),
        'hash' => generate_token(),
        'rol' => 'alumno',
        'status' => 'pendiente',
        'creado' => now()
      ];

      $data2 =
      [
        'id_alumno' => null,
        'id_grupo' => $id_grupo
      ];

      //Insertar a la base de datos
      if(!$id = alumnoModel::add(alumnoModel::$t1, $data)){
        throw new Exception(get_notificaciones(2));
      }

      $data2['id_alumno'] = $id;

      //Insertar a la base de datos
      if(!$id_ga = grupoModel::add(grupoModel::$t3, $data2)){
        throw new Exception(get_notificaciones(2));
      }

      //Email de confirmación de correo
      mail_confirmar_cuenta($id);

      $alumno = alumnoModel::by_id($id);
      $grupo = grupoModel::by_id($id_grupo);

      Flasher::new(sprintf('Alumno <b>%s</b> agregado con éxito e inscrito al grupo <b>%s</b>.', $alumno['nombre_completo'], $grupo['nombre']), 'success');
      Redirect::to('alumnos');

    } catch (PDOException $e) { //Excepciones de errores por la db
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
    try {
      if(!check_posted_data(['csrf', 'id', 'nombres', 'apellidos', 'email', 'telefono', 'password', 'conf_password', 'id_grupo'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones(0));
      }

      //validar rol
      if(!is_admin(get_user_role())){
        throw new Exception(get_notificaciones(1));
      }

      //Validar que exista el alumno
      $id = clean($_POST["id"]);
      if (!$alumno = alumnoModel::by_id($id) ) {
        throw new Exception('No existe el alumno en la base de datos.');
      }

      $db_email = $alumno['email'];
      $db_pw = $alumno['password'];
      $db_status = $alumno['status'];
      $db_id_g = $alumno['id_grupo'];

      $nombres = clean($_POST["nombres"]);
      $apellidos = clean($_POST["apellidos"]);
      $email = clean($_POST["email"]);
      $telefono = clean($_POST["telefono"]);
      $password = clean($_POST["password"]);
      $conf_password = clean($_POST["conf_password"]);
      $id_grupo = clean($_POST["id_grupo"]);
      $changed_email = $db_email === $email ? false : true;
      $changed_pw = false;
      $changed_g = $db_id_g == $id_grupo ? 0 : 1;

      //Validar la existencia del correo electrónico
      $sql = 'SELECT * FROM usuarios WHERE email = :email AND id != :id LIMIT 1';
      if (usuarioModel::query($sql, ['email' => $email, 'id' => $id])) {
        throw new Exception('El correo electrónico ya existe en la base de datos.');
      }

      //Validar que el correo sea válido
      if($changed_email && !filter_var($email, FILTER_VALIDATE_EMAIL)){
        throw new Exception('Ingresa un correo electrónico válido.');
      }

      //Validar el nombre del usuario
      if(strlen($nombres) < 3) {
        throw new Exception('Ingresa un nombre válido.');
      }

      //Validar el apellido del usuario
      if(strlen($apellidos) < 3 ) {
        throw new Exception('Ingresa un apellido válido.');
      }

      //Validar longitud de la contraseña
      $pw_ok = password_verify($db_pw, $password.AUTH_SALT);
      if(!empty($password) && $pw_ok === false && strlen($password) < 6 ) {
        throw new Exception('Ingresa una contraseña mayor a 6 caracteres.');
      }
      
      // Validar que las contraseñas sean iguales
      if(!empty($password) && $pw_ok === false && $password !== $conf_password) {
        throw new Exception('Las contraseñas no son iguales.');
      }

      //Validar que el id_grupo exista
      if($id_grupo === '' || !grupoModel::by_id($id_grupo)) {
        throw new Exception('Selecciona un grupo válido.');
      }
      
      $data = 
      [
        'nombres' => $nombres,
        'apellidos' => $apellidos,
        'nombre_completo' => sprintf('%s %s', $nombres, $apellidos),
        'email' => $email,
        'telefono' => $telefono,
        'status' => $changed_email ? 'pendiente' : $db_status
      ];

      //Actualización de contraseña
      if (!empty($password) && $pw_ok === false) {
        $data['password'] = password_hash($password.AUTH_SALT, PASSWORD_BCRYPT);
        $changed_pw = true;
      }

      //Actualizar base de datos
      if(!alumnoModel::update(alumnoModel::$t1, ['id' => $id], $data)){
        throw new Exception(get_notificaciones(2));
      }

      //Actualizar base de datos
      if ($changed_g) {
        if(!empty($db_id_g)){
          if((!grupoModel::update(grupoModel::$t3, ['id_alumno' => $id], [ 'id_grupo' => $id_grupo]))){
            throw new Exception(get_notificaciones(2));
          } 
        } else {
          grupoModel::add(grupoModel::$t3, ['id_alumno' => $id, 'id_grupo' => $id_grupo] );
        }
      }

      $alumno = alumnoModel::by_id($id);
      $grupo = grupoModel::by_id($id_grupo);

      Flasher::new(sprintf('Alumno <b>%s</b> actualizado con éxito.', $alumno['nombre_completo']), 'success');

      if ($changed_email){
        //Email de confirmación de correo
        mail_confirmar_cuenta($id);
        Flasher::new(' El correo electrónico del alumno ha sido actualizado, debe ser confirmado.');
      }

      if ($changed_pw){
        //Email de confirmación de contraseña
        mail_confirmar_cuenta($id);
        Flasher::new(' La contraseña del alumno ha sido actualizada.');
      }

      if ($changed_g){
        Flasher::new(sprintf('El grupo del alumno ha sido actualizado a <b>%s</b> con éxito.', $grupo['nombre']));
      }

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

      // Exista el alumno
         if(!$alumno = alumnoModel::by_id($id)) {
        throw new Exception('No existe el alumno en la base de datos.');
      }

      //Borramos el registro y sus conexiones
      if (alumnoModel::eliminar($alumno['id']) === false ) {
        throw new Exception(get_notificaciones(4));
      }

      //Borramos el registro y sus conexiones
      if (alumnoModel::eliminar_solo_alumno($alumno['id']) === false ) {
        throw new Exception(get_notificaciones(4));
      }

      Flasher::new(sprintf('Alumno <b>%s</b> borrado con éxito.', $alumno['nombre_completo']), 'success');
      Redirect::to('alumnos');

    } catch (PDOException $e) { //Excepciones de errores por la db
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }
}