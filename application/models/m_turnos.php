<?php if (!defined('BASEPATH')) exit('No se tiene permisos para accesar');
class M_turnos extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	// Obtiene el último turno llamado por cada módulo
	function getTurnosPantalla($area) {
		$turnos = (PRUEBAS) ? 'turnos_copia' : 'turnos';
		$turnos_mostrar = (PRUEBAS) ? 'copia_paso2' : 'turnos_paso2';
		$lista = $this->db->query("
			SELECT idturno, turno, modulo, caja, mostrado, prioritario
			FROM $turnos_mostrar
			ORDER BY idturno DESC"
		);
		// sleep(10);
		// if ($lista->num_rows() > 0) {
		// 	foreach ($lista->result() as $registro) {
		// 		if (intval($registro->mostrado) === 0) {
		// 			$this->db->query("
		// 				UPDATE $turnos
		// 				SET mostrado = 1
		// 				WHERE idturno = $registro->idturno"
		// 			);
		// 		}
		// 	}
		// }
		return $lista;
	}

	function actualizarTurnos($lista) {
		$turnos = (PRUEBAS) ? 'turnos_copia' : 'turnos';
		sleep(7);
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
	}
}
