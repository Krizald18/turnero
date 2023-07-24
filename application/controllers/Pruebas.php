<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pruebas extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('m_pruebas');
	}

	function index() {
		$dato['titulo'] = "Página de pruebas";
		$this->load->view('cabecera', $dato);
		$this->load->view('pruebas/inicio');
	}
}
