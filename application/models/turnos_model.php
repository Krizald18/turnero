<?php
class Turnos_model extends CI_Model {
  function __construct() {
    parent::__construct();
    $this->load->database();
  }

  // Función que obtiene el último turno expedido
  function ultimoTurno($idmodulo) {
    $turnos = (PRUEBAS) ? 'turnos_copia' : 'turnos';
    $d = date('Y');
    $n = date('n');
    $j = date('j');
    $query = $this->db->query("
      SELECT *
      FROM $turnos
      WHERE idmodulos = $idmodulo
        AND year(fecha)= $d
        AND month(fecha) = $n
        AND day(fecha) = $j
      ORDER BY turno DESC
      LIMIT 1"
    );
    $_turno = 0;
    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $_turno = $row->turno;
      }
    }
    return $_turno;
  }

  // Función que inserta un nuevo turno para ser entregado
  function entregarTurno($idmodulo) {
    $turnos = (PRUEBAS) ? 'turnos_copia' : 'turnos';
    $_turno = $this->ultimoTurno($idmodulo) + 1;
    $data = '';
    $fecha = "'" . date("Y") . '-' . date("m") . '-' . date("d") . "'";
    $existe = $this->db->query("
      SELECT *
      FROM $turnos
      WHERE turno = $_turno
        AND idmodulos = $idmodulo
        AND DATE_FORMAT(fecha, '%Y-%m-%d') = $fecha
      LIMIT 1 "
    );
    if ($existe->num_rows == 0) {
      $query = $this->db->query("
        INSERT INTO $turnos(fecha, turno, idmodulos, idstatus)
        VALUES(CURRENT_TIMESTAMP(), ($_turno), $idmodulo, 1)"
      );
    }
    if ($query) {
      $data = 'El turno a entregar es: ' + $_turno;
    } else {
      $data = -1;
    }
    return $data;
  }
}
