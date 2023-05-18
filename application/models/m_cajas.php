<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_cajas extends CI_Model {
  function __construct() {
    parent::__construct();
    $this->load->database();
  }

  function listarModulos() {
    $datos = array();
    $query = $this->db->query('
      SELECT idarea, area
      FROM area'
    );
    foreach ($query->result_array() as $row) {
      $datos[$row['idarea']] = $row['area'];
    }
    return $datos;
  }
  
  // Obtiene los módulos asignados a un área
  function getAllModulos() {
    $query = $this->db->query('
      SELECT idmodulos,modulo
      FROM modulos'
    );
    return $query;
  }

  function getAllCajas() {
    $query = $this->db->query('
      SELECT DISTINCT c.idcajas, c.caja, m.idarea
      FROM area a
      INNER JOIN modulos m ON a.idarea = m.idarea
      INNER JOIN cajas_asignadas ca ON ca.idmodulo = m.idmodulos
      INNER JOIN cajas c ON ca.idcaja = c.idcajas
      ORDER BY m.idarea, c.caja'
    );
    return $query;
  }

  function getTurnosPendientes($idcaja) {
    $turnos = (PRUEBAS) ? 'turnos_copia' : 'turnos';
    $now = new \DateTime('now');
    $day = $now->format('d');
    $mn = $now->format('m');
    $year = $now->format('Y');
    $query = $this->db->query("
      SELECT COUNT(*) AS pendientes, m.idmodulos
      FROM $turnos t
      INNER JOIN modulos m ON t.idmodulos = m.idmodulos
      INNER JOIN cajas_asignadas ca ON ca.idmodulo = m.idmodulos
      WHERE t.idstatus = 1
        AND day(t.fecha) = $day
        AND month(t.fecha) = $mn
        AND year(t.fecha) = $year
        AND ca.idcaja = $idcaja
      GROUP BY m.idmodulos"
    );
    return $query;
  }
  
  function getAsignaciones($idcaja) {
    //Obtengo todos los mudulos asignados a una caja
    $query = $this->db->query("
      SELECT idmodulo, modulo
      FROM cajas_asignadas ca
      JOIN modulos m ON m.idmodulos = ca.idmodulo
      WHERE idcaja = $idcaja"
    );
    return $query;
  }
  
  function llamarTurno($idcaja, $idmodulo) {
    $query = "";
    $fecha = "'" . date("Y") . '-' . date("m") . '-' . date("d") . "'";
    $query = (PRUEBAS) ?
      "call turno_pruebas($idmodulo, $fecha, $idcaja)" :
      "call turno_obtener($idmodulo, $fecha, $idcaja)";
    $result = mysql_query($query);
    if (is_resource($result)) {
      if(mysql_num_rows($result) > 0) {
        $line = mysql_fetch_array($result, MYSQL_ASSOC);
        return $line['turno'];
      }
    }
    return FALSE;
  }
}
