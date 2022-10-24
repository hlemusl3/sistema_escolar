<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de alumno
 */
class alumnoModel extends Model {
  public static $t1   = 'usuarios'; // Nombre de la tabla en la base de datos;
  
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
    $sql = 'SELECT * FROM usuarios WHERE rol = "alumno" ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function all_paginated()
  {
    // Todos los registros
    $sql = 'SELECT * FROM usuarios WHERE rol = "alumno" ORDER BY id DESC';
    return PaginationHandler::paginate($sql);
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT 
    u.*,
    ga.id_grupo 
    FROM usuarios u
    LEFT JOIN grupos_alumnos ga ON ga.id_alumno = u.id
    WHERE u.id = :id AND u.rol = "alumno" 
    LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }

  static function suspender($id)
  {
    // Suspender alumno
    return (parent::update(self::$t1, ['id' => $id], ['status' => 'suspendido']) !== false) ? true : false;
  }

  static function remover_suspension($id)
  {
    // Suspender alumno
    return (parent::update(self::$t1, ['id' => $id], ['status' => 'activo']) !== false) ? true : false;
  }

  static function eliminar($id)
  {
    $sql = 'DELETE 
    u, 
    ga 
    FROM usuarios u 
    JOIN grupos_alumnos ga ON ga.id_alumno = u.id 
    WHERE u.id = :id AND u.rol = "alumno"';
    return (parent::query($sql, ['id' => $id])) ? true : false;
  }

  static function eliminar_solo_alumno($id)
  {
    $sql = 'DELETE u FROM usuarios u WHERE u.id = :id AND u.rol = "alumno"';
    return (parent::query($sql, ['id' => $id])) ? true: false;
  }

  static function by_profesor($id)
  {
    $sql =
    'SELECT DISTINCT
        u.nombre_completo AS alumno,
        u.id AS id_alumno,
        g.nombre AS grupo,
        g.id AS id_grupo
    FROM
        usuarios u
    JOIN grupos_alumnos ga ON
        ga.id_alumno = u.id
    JOIN grupos g ON
        g.id = ga.id_grupo
    JOIN grupos_materias gm ON
        gm.id_grupo = ga.id_grupo
    JOIN materias_profesores mp ON
        mp.id = gm.id_mp
    WHERE
        mp.id_profesor = :id';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows : [];
  }

  static function stats_by_id()
  {
    $id_alumno = get_user('id');
    $materias = 0;
    $lecciones = 0;
    $tareas = 0;

    $sql = 
    'SELECT
      COUNT(DISTINCT m.id) AS total
    FROM
      materias m
    JOIN materias_profesores mp ON mp.id_materia = m.id
    JOIN grupos_materias gm ON gm.id_mp = mp.id
    JOIN grupos_alumnos ga ON ga.id_grupo = gm.id_grupo
    WHERE
      ga.id_alumno = :id';
    $materias = parent::query($sql, ['id' => $id_alumno])[0]['total'];

    $sql = 
    'SELECT
        COUNT(DISTINCT l.id) AS total
        FROM 
        lecciones l
        JOIN materias_profesores mp ON mp.id_materia = l.id_materia AND mp.id_profesor = l.id_profesor
        LEFT JOIN materias m ON m.id = mp.id_materia
        LEFT JOIN usuarios u ON u.id = mp.id_profesor AND u.rol = "profesor"
        LEFT JOIN grupos_materias gm ON gm.id_mp = mp.id
        LEFT JOIN grupos g ON g.id = gm.id_grupo
        JOIN grupos_alumnos ga ON ga.id_grupo = g.id
        WHERE ga.id_alumno = :id_alumno AND l.status IN("publica")';
    $lecciones = parent::query($sql, ['id_alumno' => $id_alumno])[0]['total'];

    $sql = 
    'SELECT
        COUNT(DISTINCT t.id) AS total
        FROM 
        tareas t
        JOIN materias_profesores mp ON mp.id_materia = t.id_materia AND mp.id_profesor = t.id_profesor
        LEFT JOIN materias m ON m.id = mp.id_materia
        LEFT JOIN usuarios u ON u.id = mp.id_profesor AND u.rol = "profesor"
        LEFT JOIN grupos_materias gm ON gm.id_mp = mp.id
        LEFT JOIN grupos g ON g.id = gm.id_grupo
        JOIN grupos_alumnos ga ON ga.id_grupo = g.id
        WHERE ga.id_alumno = :id_alumno AND t.status IN("publica")';
    $tareas = parent::query($sql, ['id_alumno' => $id_alumno])[0]['total'];

    return
    [
      'materias' => $materias,
      'lecciones' => $lecciones,
      'tareas' => $tareas
    ];
  }
}

