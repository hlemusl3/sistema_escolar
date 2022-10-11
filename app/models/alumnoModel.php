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
    $sql = 'SELECT * FROM usuarios ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM usuarios WHERE id = :id AND rol = "alumno" LIMIT 1';
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

}

