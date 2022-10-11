<?php 

class loginController extends Controller {
  function __construct()
  {
    if (Auth::validate()) {
      Flasher::new('Ya hay una sesión abierta.');
      Redirect::to('home/');
    }
  }

  function index()
  {
    $data =
    [
      'title'   => 'Ingresar a tu cuenta',
      'padding' => '0px'
    ];

    View::render('index', $data);
  }

  function post_login()
  {
    try {
    if (!Csrf::validate($_POST['csrf']) || !check_posted_data(['email','csrf','password'], $_POST)) {
      Flasher::new('Acceso no autorizado.', 'danger');
      Redirect::back();
    }

    // Data pasada del formulario
    $email  = clean($_POST['email']);
    $password = clean($_POST['password']);

    //Verificar si el email es valido
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      throw new Exception('Las credenciales no son correctas');
    }
    
    //Verifica que exista el usuario con ese email
    if(!$user = usuarioModel::by_email($email)){
      throw new Exception('Las credenciales no son correctas');
    }

    // Información del usuario loggeado, simplemente se puede reemplazar aquí con un query a la base de datos
    // para cargar la información del usuario si es existente
    if (!password_verify($password.AUTH_SALT, $user['password'])) {
      throw new Exception('Las credenciales no son correctas');
    }

    // Validar el status del usuario
    if ($user['status'] === 'pendiente') {
      mail_confirmar_cuenta($user['id']);
      throw new Exception('Confirma tu dirección de correo electrónico.');
    }

    // Loggear al usuario
    Auth::login($user['id'], $user);
    Redirect::to('dashboard');      
    
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }catch(PDOException $e){
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();    
    }
  }

  function activate()
  {
    try {
      if (!check_get_data(['email', 'hash'], $_GET)) {
        throw new Exception('El enlace de activación no es válido.');
      }

      //Data pasada en URL
      $email = clean($_GET["email"]);
      $hash = clean($_GET["hash"]);

      //Verificar que exista el usuario con ese email
      if (!$user = usuarioModel::by_email($email)) {
        throw new Exception('El enlace de activación no es válido.');
      }

      $id = $user['id'];
      $nombre = $user['nombres'];
      $status = $user['status'];
      $db_hash = $user['hash'];

      //Verificar el hash del usuario y el status
      if ($hash !== $db_hash) {
        throw new Exception('El enlace de activación no es válido.');
      }

      //Validar el status del usuario
      if ($status !== 'pendiente') {
        throw new Exception('El usuario no está pendiente de activación.');
      }

      //Activar cuenta
      if (usuarioModel::update(usuarioModel::$t1, ['id' => $id], ['status' => 'activo']) === false ) {
        throw new Exception(get_notificaciones(3));
      }

      Flasher::new(sprintf('Tu correo electrónico ha sido activado con éxito <b>%s</b>, ya puedes iniciar sesión.', $nombre), 'success');
      Redirect::to('login');

    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::to('login');
    } catch (PDOException $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::to('login');
    }
  }
}