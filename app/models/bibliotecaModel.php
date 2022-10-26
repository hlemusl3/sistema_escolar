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
    $sql = 'SELECT * FROM biblioteca ORDER BY id DESC';
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
}

