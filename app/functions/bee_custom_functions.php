<?php 

/**
 * Regresa el rol del usuario logeado
 * 
 * @return mixed
 */
function get_user_role(){
  return $rol = get_user('rol');
}