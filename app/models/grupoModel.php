<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de grupo
 */
class grupoModel extends Model {
  public static $t1   = 'grupos'; // Nombre de la tabla en la base de datos;
  
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
    $sql = 'SELECT * FROM grupos ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function all_paginated()
  {
    // Todos los registros
    $sql = 'SELECT * FROM grupos ORDER BY id DESC';
    return PaginationHandler::paginate($sql);
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM grupos WHERE id = :id LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }

  static function materias_disponibles($id)
  {
    $sql = 
    'SELECT
      mp.id,
      m.nombre AS materia,
      u.nombres AS profesor
    FROM
      materias_profesores mp
    LEFT JOIN materias m ON m.id = mp.id_materia
    LEFT JOIN usuarios u ON u.id = mp.id_profesor
    WHERE
      mp.id NOT IN (
        SELECT
          gm.id_mp
        FROM
          grupos_materias gm
        WHERE
          gm.id_grupo = :id_grupo
      )';

    return ($rows = parent::query($sql, ['id_grupo' => $id])) ? $rows : [];
  }

  static function materias_asignadas($id)
  {
    $sql = 
    'SELECT
      mp.id,
      m.id AS id_materia,
      m.nombre AS materia,
      u.id AS id_profesor,
      u.numero AS num_profesor,
      u.nombres AS profesor
    FROM
      materias_profesores mp
    LEFT JOIN materias m ON m.id = mp.id_materia
    LEFT JOIN usuarios u ON u.id = mp.id_profesor
    WHERE
      mp.id IN (
        SELECT
          gm.id_mp
        FROM
          grupos_materias gm
        WHERE
          gm.id_grupo = :id_grupo
      )';

    return ($rows = parent::query($sql, ['id_grupo' => $id])) ? $rows : [];
  }
}

