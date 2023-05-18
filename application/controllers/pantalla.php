<?php if (!defined('BASEPATH')) exit('Acceso denegado');
class Pantalla extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model('m_turnos');
  }

  function matrimonios() {
    $data['titulo'] = 'Pantalla - Sistema de Turnos';
    $this->load->view('cabecera', $data);
    $this->load->view('turnos/matrimonios');
  }

  function nacimientos() {
    $data['titulo'] = 'Turnos';
    $this->load->view('cabecera', $data);
    $this->load->view('turnos/nacimientos');
  }
  
  function pantallaTurnos() {
    // 1 matrimonios, 2 nacimientos
    $idarea = $this->input->post('idarea', TRUE);
    $turnos = $this->m_turnos->getTurnosPantalla($idarea);
    $output = [];
    if ($turnos->num_rows() > 0) {
      foreach ($turnos->result() as $row) {
        array_push(
          $output, [
            'idturno' => $row->idturno,
            'turno' => $row->turno,
            'idmodulo' => $row->idmodulos,
            'modulo' => $row->modulo,
            'caja' => $row->caja,
            'mostrado' => $row->mostrado
          ]
        );
      }
    }
    header('Content-Type: application/x-json; charset=utf-8');
    echo json_encode($output);
  }
}
