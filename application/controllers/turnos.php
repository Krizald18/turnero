<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Turnos extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('turnos_model');
  }
  
  public function dar() {
    $this->load->helper('url');
    $this->output->set_header("Access-Control-Allow-Origin: *");
    $this->output->set_header(
      "Access-Control-Expose-Headers: Access-Control-Allow-Origin"
    );
    $this->output->set_header("HTTP/1.0 200 OK");
    $this->output->set_content_type('application/json');
    $this->output->_display();
    $idmodulo = $this->input->post('idmodulo');
    $query = $this->turnos_model->entregarTurno($idmodulo);
    echo(json_encode($query));
  }
}
