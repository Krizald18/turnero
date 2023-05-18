<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Cajas extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model('m_cajas');
    $this->load->helper('url');
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->library('Session');
  }

  function index() {
    $dato['titulo'] = 'Cajas - Sistema de Turnos';
    $this->load->view('cabecera', $dato);
    $items['combo'] = $this->m_cajas->listarModulos();
    $this->load->view('cajas/inicio', $items);
  }

  function cargarModulos() {
    //Obtener el 'Id' del area seleccionada desde el formulario
    $query = $this->m_cajas->getAllModulos();
    $output = [];
    if ($query->num_rows() > 0) {
      foreach($query->result() as $row) {
        array_push(
          $output,
          ['idmodulo' => $row->idmodulos, 'modulo'=> $row->modulo]
        );
      }
    }
    header('Content-Type: application/x-json; charset=utf-8');
    echo json_encode($output);
  }

  function cargarCajas() {
    $query = $this->m_cajas->getAllCajas();
    $output = [];
    if ($query->num_rows() > 0) {
      foreach($query->result() as $row) {
        array_push(
          $output, [
            'idarea' => $row->idarea,
            'idcaja'=> $row->idcajas,
            'caja' => $row->caja
          ]
        );
      }
    }
    header('Content-Type: application/x-json; charset=utf-8');
    echo json_encode($output);
  }

  function misTurnosPendientes() {
    $idcaja = $this->input->post('idcaja', TRUE);
    $query = $this->m_cajas->getTurnosPendientes($idcaja);
    $output = [];
    if ($query->num_rows() > 0) {
      foreach($query->result() as $row){
        array_push(
          $output,
          ['pendientes' => $row->pendientes, 'idmodulo'=> $row->idmodulos]
        );
      }
    }
    header('Content-Type: application/x-json; charset=utf-8');
    echo json_encode($output);
  }

  //Obtiene los modulos asignados a una caja
  function misModulos() {
    $idcaja = $this->input->post('idcaja', TRUE);
    $resultado = $this->m_cajas->getAsignaciones($idcaja);
    $salida = [];
    if ($resultado->num_rows() > 0) {
      foreach($resultado->result() as $registro) {
        array_push(
          $salida,
          ['idmodulo' => $registro->idmodulo, 'modulo' => $registro->modulo]
        );
      }
    }
    header('Content-Type: application/x-json; charset=utf-8');
    echo json_encode($salida);
  }

  function tomarTurno() {
    $idcaja = $this->input->post('idcaja', TRUE);
    $idmodulo = $this->input->post('idmodulo', TRUE);
    $salida = '';
    $turno = $this->m_cajas->llamarTurno($idcaja, $idmodulo);
    $cajas = [
      1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15,
      60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70,
      90, 91, 92, 93, 94, 95
    ];
    $file = 'historial.txt';
    date_default_timezone_set("America/Mazatlan");
    $current = date("h:i:sa") .
      "\t" .
      $_SERVER['REMOTE_ADDR'] .
      "\t" .
      $cajas[intval($idcaja) - 1] .
      "\n" .
      file_get_contents($file);
    file_put_contents($file, $current);
    if ($turno != FALSE) {
      $salida .= $turno;
    } else {
      $salida .= "No hay pendientes";
    }
    echo $salida;
  }
  
  //Cierra la sesion de las cajas
  public function logout() {
    $this->session->unset_userdata('info');
    redirect('cajas/index');
  }
}
