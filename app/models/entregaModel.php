<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de entrega
 */
class entregaModel extends Model {
  public static $t1   = 'entregas'; // Nombre de la tabla en la base de datos;
  
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
    $sql = 'SELECT * FROM entregas ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM entregas WHERE id = :id LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }

  static function entrega_tarea_grupos($id_tarea, $id_grupo)
  {
    // Registros de entregas de tarea al grupo específico.7
    $sql = 
    'SELECT
        e.id,
        e.id_tarea,
        e.id_alumno,
        u.nombre_completo AS nombre_alumno,
        e.comentario,
        e.enlace,
        e.documento,
        e.fecha_entregado
    FROM
        entregas AS e
    JOIN usuarios u ON
        u.id = e.id_alumno
    JOIN grupos_alumnos ga ON
        ga.id_alumno = u.id
    JOIN grupos g ON
        g.id = ga.id_grupo
    WHERE
        e.id_tarea = :id_tarea AND g.id = :id_grupo';
    return PaginationHandler::paginate($sql, ['id_tarea' => $id_tarea, 'id_grupo' => $id_grupo]);
  }

  static function by_id_tarea($id_tarea)
  {
    $sql =
    'SELECT
        e.id,
        e.documento AS documento
    FROM
        entregas AS e
    JOIN tareas t ON
        e.id_tarea = t.id
    WHERE
        t.id = :id_tarea';
    return ($rows = parent::query($sql, ['id_tarea' => $id_tarea])) ? $rows[0] : [];
  }


}