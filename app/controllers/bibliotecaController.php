<?php
use \Verot\Upload\Upload;

/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de biblioteca
 */
class bibliotecaController extends Controller {
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
    if(!is_admin($this->rol)){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }

    $data = 
    [
      'title' => 'Mi Biolioteca',
      'slug' => 'biblioteca',
      'recursos' => bibliotecaModel::all()
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
      'title' => 'Agregar nuevo recurso',
      'slug' => 'biblioteca',
      'button' => ['url' => 'javascript:history.back()', 'text' => '<i class="fas fa-undo"></i> Regresar'],
      'id_profesor' => $id_profesor,
      'id_materia' => isset($_GET["id_materia"]) ? $_GET["id_materia"] : null,
      'materias_profesor' => materiaModel::materias_profesor($id_profesor)
    ];

    View::render('agregar', $data);
  }

  function post_agregar()
  {
    try {
      if(!check_posted_data(['csrf','titulo','id_materia','id_profesor','status'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones());
      }

      //Validar rol
      if(!is_profesor(get_user_role())){
        throw new Exception(get_notificaciones());
      }

      $titulo = clean($_POST["titulo"]);
      $documento = $_FILES["documento"];
      $id_materia = clean($_POST["id_materia"]);
      $id_profesor = clean($_POST["id_profesor"]);
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

      //Validar el titulol recurso
      if (strlen($titulo) < 5) {
        throw new Exception('Ingresa un título mayor a 5 caracteres.');
      }
   
      //Tarea a Guardar
      $data =
      [
        'id_materia' => $id_materia,
        'id_profesor' => $id_profesor,
        'titulo' => $titulo,
        'status' => $status,
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
      }

      //Insertar en la base de datos
      if(!$id = bibliotecaModel::add(bibliotecaModel::$t1, $data)){
        throw new Exception();
      }

      Flasher::new(sprintf('Nuevo recurso titulado <b>%s</b> agregado con éxito para la materia <b>%s</b>.', $titulo, $materia['nombre']), 'success');
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

     //Validar que exista el recurso
    if (!$recurso = bibliotecaModel::by_id($id)) {
    Flasher::new('No existe el recurso en la Base de Datos.', 'danger');
    Redirect::back();    
    }

    $id_profesor = get_user('id');

    //Validar el id del profesor y del registro
    if ($recurso['id_profesor'] !== $id_profesor && !is_admin(get_user_role())) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();    
    }

    $data =
    [
      'title' => sprintf('Recurso: %s', $recurso['titulo']),
      'slug' => 'biblioteca',
      'button' => ['url' => 'javascript:history.back()', 'text' => '<i class="fas fa-undo"></i> Regresar'],
      'id_profesor' => $id_profesor,
      'r' => $recurso
    ];

    View::render('editar', $data);
  }

  function post_editar()
  {
    try {
      if(!check_posted_data(['csrf','id','titulo','status'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones());
      }

      //Validar rol
      if(!is_profesor(get_user_role())){
        throw new Exception(get_notificaciones());
      }

      //validar que exista el documento
      $id = clean($_POST["id"]);
      if(!$recurso = bibliotecaModel::by_id($id)) {
        throw new Exception('No existe el recurso en la base de datos.');
      }

      $id_profesor = get_user('id');

      //Validar el id del profesor y del registro
      if ($recurso['id_profesor'] !== $id_profesor && !is_admin(get_user_role())) {
        throw new Exception(get_notificaciones());
      }

      $titulo = clean($_POST["titulo"]);
      $documento = $_FILES["documento"];
      $n_documento = false;
      $status = clean($_POST["status"]);
      
      $db_documento = $recurso["documento"];    

      //Validar el titulo de la recurso
      if (strlen($titulo) < 5) {
        throw new Exception('Ingresa un título mayor a 5 caracteres.');
      }

      //Recurso a Guardar
      $data =
      [
        'titulo' => $titulo,
        'status' => $status,
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
      if(!bibliotecaModel::update(bibliotecaModel::$t1, ['id' => $id], $data)){
        throw new Exception(get_notificaciones(3));
      }

      //Borrando el archivo anterior
      if ($db_documento !== null && $n_documento === true && is_file(UPLOADS.$db_documento)){
        unlink(UPLOADS.$db_documento);
      }

      Flasher::new(sprintf('Recurso titulado <b>%s</b> actualizado con éxito.', $titulo), 'success');
      Redirect::to(sprintf('grupos/materia/%s', $recurso['id_materia']));

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

      if(!is_profesor($this->rol)) {
        throw new Exception(get_notificaciones(0));
      }
  
      //Validar que exista el recurso
      if (!$recurso = bibliotecaModel::by_id($id)) {
        throw new Exception(get_notificaciones());
      }

      //Validar el id del profesor y del registro
      if ($recurso['id_profesor'] !== get_user('id') && !is_admin($this->rol)) {
        throw new Exception(get_notificaciones());
      }
      
      //Quitar el registro de la base de datos
      if(!bibliotecaModel::remove(bibliotecaModel::$t1, ['id' => $id], 1)){
        throw new Exception(get_notificaciones(4));
      }

      $documento = $recurso['documento'];

      if(!empty($documento) && is_file(UPLOADS.$documento)){
        unlink(UPLOADS.$documento);
      }

      Flasher::new(sprintf('Recurso titulado <b>%s</b> borrado con éxito.', $recurso['titulo']), 'success');
      Redirect::back();

    } catch (PDOException $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }

  function misrecursos()
  {
    if(!is_profesor($this->rol)){
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }

    $recursos = bibliotecaModel::by_profesor($this->id);
    $data =
    [
      'title' => 'Mi Biblioteca',
      'slug' => 'biblioteca',
      'recursos' => $recursos
    ];

    View::render('misrecursos',$data);
  }
}