<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de biblioteca
 */
class bibliotecaModel extends Model {
  public static $t1   = 'biblioteca'; // Nombre de la tabla en la base de datos;
  
  // Nombre de tabla 2 que talvez tenga conexión con registros
  //public static $t2 = '__tabla 2___'; 
  //public static $t3 = '__tabla 3___'; 

  function __construct()
  {
    // Constructor general
  }
  
  static function all()
  {
    // Todos los registros
    $sql = 
    'SELECT 
    r.*, 
    u.nombre_completo AS profesor,
    m.nombre AS materia
    FROM biblioteca r
    LEFT JOIN usuarios u ON u.id = id_profesor
    LEFT JOIN materias m ON m.id = r.id_materia
    ORDER BY r.id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM biblioteca WHERE id = :id LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }

  static function by_materia_profesor($id_materia, $id_profesor)
  {
    $sql =
    'SELECT
      b.*,
      m.nombre AS materia,
      u.nombre_completo AS profesor
    FROM
      biblioteca b
    JOIN materias_profesores mp ON mp.id_materia = b.id_materia AND mp.id_profesor = b.id_profesor
    LEFT JOIN materias m ON m.id = mp.id_materia
    LEFT JOIN usuarios u ON u.id = mp.id_profesor AND u.rol="profesor"
    WHERE
      b.id_materia = :id_materia AND b.id_profesor = :id_profesor';

      return PaginationHandler::paginate($sql, ['id_materia' => $id_materia, 'id_profesor' => $id_profesor]);
  }

  static function by_profesor($id)
  {
    // Todos los registros
    $sql = 'SELECT 
    r.*,
    m.nombre AS materia
    FROM biblioteca r
    LEFT JOIN materias m ON m.id = r.id_materia
    WHERE r.id_profesor = :id
    ORDER BY r.id DESC';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows : [];
  }

  static function by_alumno($id_alumno, $publicadas = true, $id_materia = null, $id_profesor = null)
  {
    // Todos los recursos publicados
    if ($publicadas === true) {
        $sql =
        'SELECT
        r.*,
        m.nombre AS materia,
        u.nombre_completo AS profesor
        FROM 
        biblioteca r
        JOIN materias_profesores mp ON mp.id_materia = r.id_materia AND mp.id_profesor = r.id_profesor
        LEFT JOIN materias m ON m.id = mp.id_materia
        LEFT JOIN usuarios u ON u.id = mp.id_profesor AND u.rol = "profesor"
        LEFT JOIN grupos_materias gm ON gm.id_mp = mp.id
        LEFT JOIN grupos g ON g.id = gm.id_grupo
        JOIN grupos_alumnos ga ON ga.id_grupo = g.id
        WHERE ga.id_alumno = :id_alumno AND r.status IN("publica") '.($id_materia === null || $id_profesor === null ? '' : 'AND r.id_materia = :id_materia AND r.id_profesor = :id_profesor').
        ' ORDER BY r.creado DESC';
    
        $data =
        [
          'id_alumno' => $id_alumno
        ];
    
        if ($id_materia !== null && $id_profesor !== null) {
          $data['id_materia'] = $id_materia;
          $data['id_profesor'] = $id_profesor;
        }
    
        return ($rows = parent::query($sql, $data)) ? $rows : [];      
    }

    // Todas los foros sin importar su status
    $sql =
    'SELECT
    f.*,
    m.nombre AS materia,
    u.nombre_completo AS profesor
    FROM 
    foros f
    JOIN materias_profesores mp ON mp.id_materia = f.id_materia AND mp.id_profesor = f.id_profesor
    LEFT JOIN materias m ON m.id = mp.id_materia
    LEFT JOIN usuarios u ON u.id = mp.id_profesor AND u.rol = "profesor"
    LEFT JOIN grupos_materias gm ON gm.id_mp = mp.id
    LEFT JOIN grupos g ON g.id = gm.id_grupo
    JOIN grupos_alumnos ga ON ga.id_grupo = g.id
    WHERE ga.id_alumno = :id_alumno '.($id_materia === null || $id_profesor === null ? '' : 'AND f.id_materia = :id_materia AND f.id_profesor = :id_profesor');

    $data =
    [
      'id_alumno' => $id_alumno
    ];

    if ($id_materia !== null && $id_profesor !== null) {
      $data['id_materia'] = $id_materia;
      $data['id_profesor'] = $id_profesor;
    }

    return PaginationHandler::paginate($sql, $data);
  }


}

