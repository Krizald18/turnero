<?php
class Turnos_model extends CI_Model {
  function __construct() {
    parent::__construct();
    $this->load->database();
  }

  function entregarReporte($reporte) {
    $data = $this->db->query("
      SELECT *
      FROM $reporte"
    );
    return $data;
  }
}
