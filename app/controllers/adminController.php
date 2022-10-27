<?php

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de admin
 */
class adminController extends Controller {
  private $id = null;
  private $rol = null;
  
  function __construct()
  {
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }

    $this->id  = get_user('id');
    $this->rol = get_user_role();

    if(!is_admin($this->rol)){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }

  }
  
  function index()
  {
    $data = 
    [
      'title' => 'Administración',
      'slug' => 'admin',
      'button' => ['url' => 'admin/agregar', 'text' => '<i class="fas fa-plus"></i> Agregar administrador'],
      'admins' => usuarioModel::all_admin()
    ];
    
    // Descomentar vista si requerida
    View::render('index', $data);
  }

  function agregar()
  {
    if(!is_admin($this->rol)){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }

    $data = 
    [
      'title' => 'Agregar administrador',
      'slug' => 'admin',
      'button' => ['url' => 'admin', 'text' => '<i class="fas fa-table"></i> Todos los administradores']
    ];


    View::render('agregar', $data);
  }

  function post_agregar()
  {
    try {
      if(!check_posted_data(['csrf', 'nombres', 'apellidos', 'email', 'telefono', 'password', 'conf_password'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones(0));
      }

      //validar rol
      if(!is_admin($this->rol)){
        throw new Exception(get_notificaciones(1));
      }
      
      $nombres = clean($_POST["nombres"]);
      $apellidos = clean($_POST["apellidos"]);
      $email = clean($_POST["email"]);
      $telefono = clean($_POST["telefono"]);
      $password = clean($_POST["password"]);
      $conf_password = clean($_POST["conf_password"]);

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
        'rol' => 'admin',
        'status' => 'pendiente',
        'creado' => now()
      ];

      //Insertar a la base de datos
      if(!$id = usuarioModel::add(usuarioModel::$t1, $data)){
        throw new Exception(get_notificaciones(2));
      }

      //Email de confirmación de correo
      mail_confirmar_cuenta($id);

      $admin = usuarioModel::by_id($id);

      Flasher::new(sprintf('Administrador <b>%s</b> agregado con éxito.', $admin['nombre_completo']),'success');
      Redirect::to('admin');

    } catch (PDOException $e) { //Excepciones de errores por la db
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }

  }

  function ver($id)
  {
    if(!is_admin($this->rol)){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }

    if(!$admin = usuarioModel::by_id($id)){
      Flasher::new('No existe el administrador en la base de datos.', 'danger');
      Redirect::back();
    }

    $data =
    [
      'title' => sprintf('Administrador(a) %s', $admin['nombre_completo']),
      'slug' => 'admin',
      'button' => ['url' => 'admin', 'text' => '<i class="fas fa-table"></i> Todos los administradores'],
      'a' => $admin
    ];

    View::render('ver', $data);
  }

  function post_editar()
  {
    try {
      if(!check_posted_data(['csrf', 'id', 'nombres', 'apellidos', 'email', 'telefono', 'password', 'conf_password'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones(0));
      }

      //validar rol
      if(!is_admin($this->rol)){
        throw new Exception(get_notificaciones(1));
      }

      //Validar que exista el admin
      $id = clean($_POST["id"]);
      if (!$admin = usuarioModel::by_id($id) ) {
        throw new Exception('No existe el administrador en la base de datos.');
      }

      $db_email = $admin['email'];
      $db_pw = $admin['password'];
      $db_status = $admin['status'];
      $db_id_g = $admin['id_grupo'];

      $nombres = clean($_POST["nombres"]);
      $apellidos = clean($_POST["apellidos"]);
      $email = clean($_POST["email"]);
      $telefono = clean($_POST["telefono"]);
      $password = clean($_POST["password"]);
      $conf_password = clean($_POST["conf_password"]);
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
      if(!usuarioModel::update(usuarioModel::$t1, ['id' => $id], $data)){
        throw new Exception(get_notificaciones(2));
      }

      $admin = adminModel::by_id($id);

      Flasher::new(sprintf('Administrador <b>%s</b> actualizado con éxito.', $admin['nombre_completo']), 'success');

      if ($changed_email){
        //Email de confirmación de correo
        mail_confirmar_cuenta($id);
        Flasher::new(' El correo electrónico del admin ha sido actualizado, debe ser confirmado.');
      }

      if ($changed_pw){
        //Email de confirmación de contraseña
        mail_confirmar_cuenta($id);
        Flasher::new(' La contraseña del admin ha sido actualizada.');
      }

      Redirect::to('admin');

    } catch (PDOException $e) { //Excepciones de errores por la db
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }

  function suspender($id)
  {
    $admin = usuarioModel::by_id($id);
    $data = 
    [
      'status' => 'suspendido'
    ];
      //Actualizar base de datos
      if(!usuarioModel::update(usuarioModel::$t1, ['id' => $id], $data)){
        throw new Exception(get_notificaciones(2));
      }
      Flasher::new('Se suspendió el usuario '.$admin['nombre_completo']);
      Redirect::to('admin');
  }

  function remover_suspension($id)
  {
    $admin = usuarioModel::by_id($id);
    $data = 
    [
      'status' => 'activo'
    ];
      //Actualizar base de datos
      if(!usuarioModel::update(usuarioModel::$t1, ['id' => $id], $data)){
        throw new Exception(get_notificaciones(2));
      }
      Flasher::new('Se removió la suspensión del usuario '.$admin['nombre_completo']);
      Redirect::to('admin');
  }

  function borrar($id)
  {
    try {
      if(!check_get_data(['_t'], $_GET) || !Csrf::validate($_GET['_t'])){
        throw new Exception(get_notificaciones(0));
      }

      //validar rol
      if(!is_admin($this->rol)){
        throw new Exception(get_notificaciones(1));
      }

      // Exista el admin
         if(!$admin = usuarioModel::by_id($id)) {
        throw new Exception('No existe el admin en la base de datos.');
      }

      //Borramos el registro y sus conexiones
      if (!usuarioModel::remove(usuarioModel::$t1, ['id' => $id])) {
        throw new Exception(get_notificaciones(4));
      }

      Flasher::new(sprintf('Admin <b>%s</b> borrado con éxito.', $admin['nombre_completo']), 'success');
      Redirect::to('admin');

    } catch (PDOException $e) { //Excepciones de errores por la db
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }
}