<?php if (!defined('BASEPATH')) exit('Algo salio mal');
class Usuarios extends CI_Model {

  protected $tabla;

  protected $id;

  /*
    Constructor del modelo, aqui establecemos
    que tabla utilizamos y cual es su llave primaria.
  */
  function __construct() {
    parent::__construct();
    $this->tabla = 'usuarios';
    $this->id = 'id';
  }

  function get($username = '', $password = '') {
    return $this->db->query("
      SELECT *
      FROM usuarios
      WHERE username = '$username'
        AND password = '$password'"
    )->row();
  }
}
