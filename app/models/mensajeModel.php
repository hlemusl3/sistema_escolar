<?php

/**
 * Plantilla general de modelos
 * Versión 1.0.1
 *
 * Modelo de mensaje
 */
class mensajeModel extends Model {
  public static $t1   = 'mensajes'; // Nombre de la tabla en la base de datos;
  
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
    $sql = 'SELECT * FROM mensajes ORDER BY id DESC';
    return ($rows = parent::query($sql)) ? $rows : [];
  }

  static function by_id($id)
  {
    // Un registro con $id
    $sql = 'SELECT * FROM mensajes WHERE id = :id LIMIT 1';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows[0] : [];
  }

  static function recibidos_by_id_user($id)
  {
    $sql = 'SELECT m.*, u.nombre_completo AS remitente FROM mensajes m JOIN usuarios u ON u.id = m.id_remitente  WHERE id_destinatario = :id ORDER BY m.fecha DESC';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows : [];
  }

  static function enviados_by_id_user($id)
  {
    $sql = 'SELECT m.*, u.nombre_completo AS destinatario FROM mensajes m JOIN usuarios u ON u.id = m.id_destinatario  WHERE id_remitente = :id ORDER BY m.fecha DESC';
    return ($rows = parent::query($sql, ['id' => $id])) ? $rows : [];
  }

  static function total_no_leidos()
  {
    $id = get_user('id');
    $sql      = 'SELECT COUNT(m.id) AS total FROM mensajes m WHERE m.id_destinatario = :id AND m.estado = "noleido"';
    return $mensajes = parent::query($sql, ['id' => $id])[0]['total'];
  }
}

