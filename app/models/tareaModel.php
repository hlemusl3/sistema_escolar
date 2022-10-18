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
    $sql = 'SELECT * FROM tareas ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 
    'SELECT 
    t.*,
    m.nombre AS materia
    FROM tareas t
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

}

