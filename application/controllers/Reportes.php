<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Reportes extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('reportes_model');
  }
  
  public function imprimir() {
    $this->load->helper('url');
    $this->output->set_header("Access-Control-Allow-Origin: *");
    $this->output->set_header(
      "Access-Control-Expose-Headers: Access-Control-Allow-Origin"
    );
    $this->output->set_header("HTTP/1.0 200 OK");
    $this->output->set_content_type('application/json');
    $this->output->_display();
    $reporte = $this->input->post('reporte', FALSE);
    $query = $this->turnos_model->entregarTurno($reporte);
    echo(json_encode($query));
  }
}
