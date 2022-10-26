<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de foro
 */
class foroModel extends Model {
  public static $t1   = 'foros'; // Nombre de la tabla en la base de datos;
  public static $t2   = 'respuestas_foros';
  // Nombre de tabla 2 que talvez tenga conexión con registros
  //public static $t3 = '__tabla 3___'; 

  function __construct()
  {
    // Constructor general
  }
  
  static function all()
  {
    // Todos los registros
    $sql = 'SELECT * FROM foros ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM foros WHERE id = :id LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }

  static function by_materia_profesor($id_materia, $id_profesor)
  {
    $sql =
    'SELECT
      f.*,
      m.nombre AS materia,
      u.nombre_completo AS profesor
    FROM
      foros f
    JOIN materias_profesores mp ON mp.id_materia = f.id_materia AND mp.id_profesor = f.id_profesor
    LEFT JOIN materias m ON m.id = mp.id_materia
    LEFT JOIN usuarios u ON u.id = mp.id_profesor AND u.rol="profesor"
    WHERE
      f.id_materia = :id_materia AND f.id_profesor = :id_profesor';

      return PaginationHandler::paginate($sql, ['id_materia' => $id_materia, 'id_profesor' => $id_profesor]);
  }

  static function respuestas($id)
  {
    $sql = 
    'SELECT
      rf.*,
      u.nombre_completo AS usuario
    FROM
      respuestas_foros rf
    JOIN usuarios u ON u.id = rf.id_usuario
    WHERE rf.id_foro = :id';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows : [];
  }

  static function by_id_foro($id_foro)
  {
    $sql =
    'SELECT
        r.id
    FROM
        respuestas_foros AS r
    JOIN foros f ON
        r.id_foro = f.id
    WHERE
        f.id = :id_foro';
    return ($rows = parent::query($sql, ['id_foro' => $id_foro])) ? $rows[0] : [];
  }

  static function by_profesor($id)
  {
    // Todos los registros
    $sql = 'SELECT 
    f.*,
    m.nombre AS materia
    FROM foros f 
    LEFT JOIN materias m ON m.id = f.id_materia
    WHERE f.id_profesor = :id
    ORDER BY f.id DESC';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows : [];
  }
}

