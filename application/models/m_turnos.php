<?php if (!defined('BASEPATH')) exit('No se tiene permisos para accesar');
class M_turnos extends CI_Model {
  function __construct() {
    parent::__construct();
    $this->load->database();
  }
  
  // Obtiene el último turno llamado por cada módulo
  function getTurnosPantalla($area) {
    $y = date('Y');
    $m = date('n');
    $d = date('j');
    $turnos = (PRUEBAS) ? 'turnos_copia' : 'turnos';
    // En un futuro se creará una vista para mostrar los turnos de prueba
    $turnos_mostrar = (PRUEBAS) ? 'turnos_paso2' : 'turnos_paso2';
    $lista = $this->db->query("
      SELECT *
      FROM $turnos_mostrar
      ORDER BY idturno DESC"
    );
    sleep(14);
    if ($lista->num_rows() > 0) {
      foreach ($lista->result() as $registro) {
        if (intval($registro->mostrado) === 0) {
          $this->db->query("
            UPDATE $turnos
            SET mostrado = 1
            WHERE idturno = $registro->idturno"
          );
        }
      }
    }
    return $lista;
  }
}
