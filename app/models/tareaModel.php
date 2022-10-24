<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de tarea
 */
class tareaModel extends Model {
  public static $t1   = 'tareas'; // Nombre de la tabla en la base de datos;
  
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
    t.*, 
    u.nombre_completo AS profesor,
    m.nombre AS materia
    FROM tareas t
    LEFT JOIN usuarios u ON u.id = id_profesor
    LEFT JOIN materias m ON m.id = t.id_materia
    ORDER BY t.id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function all_paginated()
  {
    // Todos los registros paginados
    $sql = 
    'SELECT 
    t.*,
    u.nombre_completo AS profesor,
    m.nombre AS materia
    FROM tareas t
    LEFT JOIN usuarios u ON u.id = t.id_profesor
    LEFT JOIN materias m ON m.id = t.id_materia
    ORDER BY t.id DESC';
    return PaginationHandler::paginate($sql);
  }


  static function by_id($id)
  {
    // Un registro con $id
    $sql = 
    'SELECT 
    t.*,
    m.nombre AS materia,
    p.nombre_completo AS profesor
    FROM tareas t
    LEFT JOIN usuarios p ON p.id = t.id_profesor AND p.rol = "profesor"
    LEFT JOIN materias m ON m.id = t.id_materia  
    WHERE t.id = :id LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }

  static function by_materia_profesor($id_materia, $id_profesor)
  {
    $sql =
    'SELECT
      t.*,
      m.nombre AS materia,
      u.nombre_completo AS profesor
    FROM
      tareas t
    JOIN materias_profesores mp ON mp.id_materia = t.id_materia AND mp.id_profesor = t.id_profesor
    LEFT JOIN materias m ON m.id = mp.id_materia
    LEFT JOIN usuarios u ON u.id = mp.id_profesor AND u.rol="profesor"
    WHERE
      t.id_materia = :id_materia AND t.id_profesor = :id_profesor';

      return PaginationHandler::paginate($sql, ['id_materia' => $id_materia, 'id_profesor' => $id_profesor]);
  }

  static function by_alumno($id_alumno, $publicadas = true, $id_materia = null, $id_profesor = null)
  {
    // Todas las tareas publicadas
    if ($publicadas === true) {
        $sql =
        'SELECT
        t.*,
        m.nombre AS materia,
        u.nombre_completo AS profesor
        FROM 
        tareas t
        JOIN materias_profesores mp ON mp.id_materia = t.id_materia AND mp.id_profesor = t.id_profesor
        LEFT JOIN materias m ON m.id = mp.id_materia
        LEFT JOIN usuarios u ON u.id = mp.id_profesor AND u.rol = "profesor"
        LEFT JOIN grupos_materias gm ON gm.id_mp = mp.id
        LEFT JOIN grupos g ON g.id = gm.id_grupo
        JOIN grupos_alumnos ga ON ga.id_grupo = g.id
        WHERE ga.id_alumno = :id_alumno AND t.status IN("publica") '.($id_materia === null || $id_profesor === null ? '' : 'AND t.id_materia = :id_materia AND t.id_profesor = :id_profesor').
        ' ORDER BY m.id DESC, t.fecha_inicial DESC';
    
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

    // Todas las tareas sin importar su status
    $sql =
    'SELECT
    t.*,
    m.nombre AS materia,
    u.nombre_completo AS profesor
    FROM 
    tareas t
    JOIN materias_profesores mp ON mp.id_materia = t.id_materia AND mp.id_profesor = t.id_profesor
    LEFT JOIN materias m ON m.id = mp.id_materia
    LEFT JOIN usuarios u ON u.id = mp.id_profesor AND u.rol = "profesor"
    LEFT JOIN grupos_materias gm ON gm.id_mp = mp.id
    LEFT JOIN grupos g ON g.id = gm.id_grupo
    JOIN grupos_alumnos ga ON ga.id_grupo = g.id
    WHERE ga.id_alumno = :id_alumno '.($id_materia === null || $id_profesor === null ? '' : 'AND t.id_materia = :id_materia AND t.id_profesor = :id_profesor');

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

  static function by_profesor($id)
  {
    // Todos los registros
    $sql = 'SELECT 
    t.*,
    m.nombre AS materia
    FROM tareas t 
    LEFT JOIN materias m ON m.id = t.id_materia
    WHERE t.id_profesor = :id
    ORDER BY t.id DESC';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows : [];
  }
}