<?php
use \Verot\Upload\Upload;
/**
 * Plantilla general de controladores
 * Versión 1.0.2
 *
 * Controlador de alumno
 */
class alumnoController extends Controller {
  private $id_alumno = null;
  private $rol = null;

  function __construct()
  {
    //Validación de sesión de usuario
    if (!Auth::validate()) {
      Flasher::new('Debes iniciar sesión primero.', 'danger');
      Redirect::to('login');
    }

    //Inicializo valores de propiedades
    $this->id_alumno = get_user('id');
    $this->rol = get_user_role();
    
    if(is_admin($this->rol)){
      Redirect::to('alumnos');
    }

    if(!is_alumno($this->rol)) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::back();
    }
  }
  
  function index()
  {
    $this->grupo();
  }

  function grupo()
  {
    if (!$grupo = grupoModel::by_alumno($this->id_alumno)) {
      Flasher::new('El grupo no existe en la base de datos.','danger');
      Redirect::back();
    }

    $data = 
    [
      'title' => $grupo['nombre'],
      'slug'   => 'alumno-grupo',
      'g' => $grupo
    ];
    
    View::render('grupo', $data);
  }

  function lecciones()
  {
    $publicadas = true;
    $id_materia = isset($_GET["id_materia"]) ? clean($_GET["id_materia"], true) : null;
    $id_profesor = isset($_GET["id_profesor"]) ? clean($_GET["id_profesor"], true) : null;

    $data =
    [
      'title' => 'Todas mis lecciones',
      'slug' => 'alumno-lecciones',
      'lecciones' => leccionModel::by_alumno($this->id_alumno, $publicadas, $id_materia, $id_profesor)
    ];
    View::render('lecciones', $data);
  }

  function tareas()
  {
    $publicadas = true;
    $id_materia = isset($_GET["id_materia"]) ? clean($_GET["id_materia"], true) : null;
    $id_profesor = isset($_GET["id_profesor"]) ? clean($_GET["id_profesor"], true) : null;

    $data =
    [
      'title' => 'Todas mis tareas',
      'slug' => 'alumno-tareas',
      'tareas' => tareaModel::by_alumno($this->id_alumno, $publicadas, $id_materia, $id_profesor)
    ];

    View::render('tareas', $data);
  }

  function leccion($id_leccion)
  {
    //Validar que exista la leccion
    if(!$leccion = leccionModel::by_id($id_leccion)){
      Flasher::new('No existe la lección seleccionada.', 'danger');
      Redirect::back();
    }

    //Validar si la lección le pertenece al grupo del alumno / materia
    $sql = 
    'SELECT
      u.*
    FROM
      usuarios u
    JOIN grupos_alumnos ga ON ga.id_alumno = u.id
    JOIN grupos g ON g.id = ga.id_grupo
    JOIN grupos_materias gm ON gm.id_grupo = g.id
    JOIN materias_profesores mp ON mp.id = gm.id_mp
    JOIN lecciones l ON l.id_materia = mp.id_materia
    AND l.id_profesor = mp.id_profesor
    WHERE
      u.id = :id_usuario
    AND l.id = :id_leccion LIMIT 1';

    if (!leccionModel::query($sql, ['id_usuario' => $this->id_alumno, 'id_leccion' => $id_leccion])) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::to('alumno/lecciones');
    }

    //Guardar las fechas de inicio y finalización de la lección
    $time = time();
    $min = strtotime($leccion['fecha_inicial']);
    $max = strtotime($leccion['fecha_disponible']);

    //Validar el acceso con base a la fecha inicial
    if(($min - $time) > 0){
      Flasher::new(sprintf('Esta lección aún no está disponible, lo estará el día <b>%s</b>.', format_date($leccion['fecha_inicial'])), 'danger');
      Redirect::to('alumno/lecciones');
    }

    //Validar el acceso con base a la fecha final
    if (($max - $time) < 0) {
      Flasher::new(sprintf('Esta lección ya no está disponible, caducó el día <b>%s</b>.', format_date($leccion['fecha_disponible'])),'danger');
      Redirect::to('alumno/lecciones');
    }

    $data = 
    [
      'title' => sprintf('Leccion %s', $leccion['titulo']),
      'hide_title' => true,
      'slug' => 'alumno-lecciones',
      'l' => $leccion
    ];

    View::render('leccion', $data);
  }

  function tarea($id_tarea)
  {
    //Validar que exista la tarea
    if(!$tarea = tareaModel::by_id($id_tarea)){
      Flasher::new('No existe la tarea seleccionada.', 'danger');
      Redirect::back();
    }

    //Validar si la tarea le pertenece al grupo del alumno / materia
    $sql = 
    'SELECT
      u.*
    FROM
      usuarios u
    JOIN grupos_alumnos ga ON ga.id_alumno = u.id
    JOIN grupos g ON g.id = ga.id_grupo
    JOIN grupos_materias gm ON gm.id_grupo = g.id
    JOIN materias_profesores mp ON mp.id = gm.id_mp
    JOIN tareas t ON t.id_materia = mp.id_materia
    AND t.id_profesor = mp.id_profesor
    WHERE
      u.id = :id_usuario
    AND t.id = :id_tarea LIMIT 1';

    if (!tareaModel::query($sql, ['id_usuario' => $this->id_alumno, 'id_tarea' => $id_tarea])) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::to('alumno/tareas');
    }

    //Guardar las fechas de inicio y finalización de la tarea
    $time = time();
    $min = strtotime($tarea['fecha_inicial']);
    $max = strtotime($tarea['fecha_disponible']);

    //Validar el acceso con base a la fecha inicial
    if(($min - $time) > 0){
      Flasher::new(sprintf('Esta tarea aún no está disponible, lo estará el día <b>%s</b>.', format_date($tarea['fecha_inicial'])), 'danger');
      Redirect::to('alumno/tareas');
    }

    //Validar el acceso con base a la fecha final
    if (($max - $time) < 0) {
      Flasher::new(sprintf('Esta tarea ya no está disponible, caducó el día <b>%s</b>.', format_date($tarea['fecha_disponible'])),'danger');
      Redirect::to('alumno/tareas');
    }

    $entrega = entregaModel::by_id_tarea($id_tarea);
    
    $data = 
    [
      'title' => sprintf('Tarea %s', $tarea['titulo']),
      'hide_title' => true,
      'slug' => 'alumno-tareas',
      't' => $tarea,
      'id_tarea' => $id_tarea,
      'id_entrega' => $entrega['id'] = (!empty($entrega)) ? $entrega['id'] : null 
    ];

    View::render('tarea', $data);
  }

  function entrega($id)
  {
    $entrega = entregaModel::by_id($id);
    $id_tarea = $entrega['id_tarea'];
    $id_alumno = $entrega['id_alumno'];
    $alumno = usuarioModel::by_id($id_alumno);
    $tarea = tareaModel::by_id($id_tarea);
    $grupo = grupoModel::grupo_alumno($id_alumno);
    $id_grupo = $grupo['id_grupo'];    
    $data = 
    [
      'title' => sprintf('Entrega del alumno <b>%s</b> para la tarea <b>%s</b>', $alumno['nombre_completo'], $tarea['titulo']),
      'slug'   => 'tareas',
      'button' => ['url' => sprintf('alumno/tarea/%s', $id_tarea), 'text' => '<i class="fas fa-undo"></i> Regresar'],
      'entrega' => $entrega,
      'alumno' => $alumno
    ];
    View::render('entrega', $data);
  }

  function entregar($id_tarea)
  {
    $tarea = tareaModel::by_id($id_tarea);
    $alumno = get_user('nombre_completo');
    $data = 
    [
      'title' => sprintf('Agregar entrega a la tarea %s', $tarea['titulo']),
      'slug' => 'tareas',
      'button' => ['url' => sprintf('alumno/tarea/%s', $id_tarea), 'text' => '<i clas="fas fa-undo"></i> Regresar'],
      'id_tarea' => $tarea['id'],
      'id_alumno' => $this->id_alumno,
      'alumno' => $alumno
    ];
    View::render('entregar', $data);
  }

  function post_entregar()
  {
    try {
      if(!check_posted_data(['csrf','id_tarea','id_alumno','comentario','enlace'], $_POST) || !Csrf::validate($_POST['csrf'])){
        throw new Exception(get_notificaciones());
      }

      $id_tarea = clean($_POST["id_tarea"]);
      $id_alumno = clean($_POST["id_alumno"]);
      $comentario = clean($_POST["comentario"]);
      $enlace = clean($_POST["enlace"]);
      $documento = $_FILES["documento"];

      //Validar el enlace
      if (!filter_var($enlace, FILTER_VALIDATE_URL) && !empty($enlace)) {
        throw new Exception('Ingresa un enlace (URL) válido.');
      }

      //Entrega a guardar
      $data =
      [
        'id_tarea' => $id_tarea,
        'id_alumno' => $id_alumno,
        'comentario' => $comentario,
        'enlace' => $enlace,
        'fecha_entregado' => now()
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
      if(!$id = entregaModel::add(entregaModel::$t1, $data)){
        throw new Exception();
      }

      Flasher::new('Tarea entregada con éxito');
      Redirect::to(sprintf('alumno/tarea/%s', $id_tarea));

    } catch (PDOException $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    } catch (Exception $e) {
      Flasher::new($e->getMessage(), 'danger');
      Redirect::back();
    }
  }

  function foros()
  {
    $publicadas = true;
    $id_materia = isset($_GET["id_materia"]) ? clean($_GET["id_materia"], true) : null;
    $id_profesor = isset($_GET["id_profesor"]) ? clean($_GET["id_profesor"], true) : null;

    $data =
    [
      'title' => 'Todos mis foros',
      'slug' => 'foros',
      'foros' => foroModel::by_alumno($this->id_alumno, $publicadas, $id_materia, $id_profesor)
    ];
    View::render('foros', $data);
  }

  function foro($id_foro)
  {
    //Validar que exista la foro
    if(!$foro = foroModel::by_id($id_foro)){
      Flasher::new('No existe el foro seleccionado.', 'danger');
      Redirect::back();
    }

    //Validar el foro le pertenece al grupo del alumno / materia
    $sql = 
    'SELECT
      u.*
    FROM
      usuarios u
    JOIN grupos_alumnos ga ON ga.id_alumno = u.id
    JOIN grupos g ON g.id = ga.id_grupo
    JOIN grupos_materias gm ON gm.id_grupo = g.id
    JOIN materias_profesores mp ON mp.id = gm.id_mp
    JOIN foros f ON f.id_materia = mp.id_materia
    AND f.id_profesor = mp.id_profesor
    WHERE
      u.id = :id_usuario
    AND f.id = :id_foro LIMIT 1';

    if (!foroModel::query($sql, ['id_usuario' => $this->id_alumno, 'id_foro' => $id_foro])) {
      Flasher::new(get_notificaciones(), 'danger');
      Redirect::to('alumno/foros');
    }

    //Guardar las fechas de inicio y finalización de la lección
    $time = time();
    $min = strtotime($foro['fecha_inicial']);
    $max = strtotime($foro['fecha_disponible']);

    //Validar el acceso con base a la fecha inicial
    if(($min - $time) > 0){
      Flasher::new(sprintf('Este foro aún no está disponible, lo estará el día <b>%s</b>.', format_date($foro['fecha_inicial'])), 'danger');
      Redirect::to('alumno/foros');
    }

    //Validar el acceso con base a la fecha final
    if (($max - $time) < 0) {
      Flasher::new(sprintf('Este foro ya no está disponible, caducó el día <b>%s</b>.', format_date($foro['fecha_disponible'])),'danger');
      Redirect::to('alumno/foros');
    }

    $data = 
    [
      'title' => sprintf('Foro %s', $foro['titulo']),
      'hide_title' => true,
      'slug' => 'foros',
      'f' => $foro,
      'r' => foroModel::respuestas($id_foro)
    ];

    View::render('foro', $data);
  }

  function biblioteca()
  {
    $publicadas = true;
    $id_materia = isset($_GET["id_materia"]) ? clean($_GET["id_materia"], true) : null;
    $id_profesor = isset($_GET["id_profesor"]) ? clean($_GET["id_profesor"], true) : null;

    $data =
    [
      'title' => 'Mi Biblioteca',
      'slug' => 'biblioteca',
      'recursos' => bibliotecaModel::by_alumno($this->id_alumno, $publicadas, $id_materia, $id_profesor)
    ];
    View::render('biblioteca', $data);
  }

}