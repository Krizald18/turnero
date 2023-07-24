<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_cajas extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function listarModulos() {
		$datos = array();
		$query = $this->db->query("
			SELECT idarea, area
			FROM area"
		);
		foreach ($query->result_array() as $row) {
			$datos[$row['idarea']] = $row['area'];
		}
		return $datos;
	}
	
	// Obtiene los módulos asignados a un área
	function getAllModulos() {
		$query = $this->db->query("
			SELECT idmodulos,modulo
			FROM modulos"
		);
		return $query;
	}

	function getAllCajas() {
		$query = $this->db->query("
			SELECT DISTINCT c.idcajas, c.caja, m.idarea
			FROM area a
			INNER JOIN modulos m ON a.idarea = m.idarea
			INNER JOIN cajas_asignadas ca ON ca.idmodulo = m.idmodulos
			INNER JOIN cajas c ON ca.idcaja = c.idcajas
			ORDER BY m.idarea, c.caja"
		);
		return $query;
	}

	function getTurnosPendientes($idcaja) {
		$tablaturnos = (PRUEBAS) ? 'turnos_copia' : 'turnos';
		$fecha = date("'Y-m-d'");
		$query = $this->db->query("
			SELECT COUNT(*) AS pendientes, m.idmodulos
			FROM $tablaturnos t
			INNER JOIN modulos m ON t.idmodulos = m.idmodulos
			INNER JOIN cajas_asignadas ca ON ca.idmodulo = m.idmodulos
			WHERE t.idstatus = 1
				AND date_format(t.fecha, '%Y-%m-%d') = $fecha
				AND ca.idcaja = $idcaja
			GROUP BY m.idmodulos"
		);
		return $query;
	}
	
	function getAsignaciones($idcaja) {
		//Obtengo todos los módulos asignados a una caja
		$query = $this->db->query("
			SELECT idmodulo, modulo
			FROM cajas_asignadas ca
			JOIN modulos m ON m.idmodulos = ca.idmodulo
			WHERE idcaja = $idcaja"
		);
		return $query;
	}
	
	function llamarTurno($idcaja, $idmodulo) {
		$tablaturnos = (PRUEBAS) ? 'turnos_copia' : 'turnos';
		$vistaturnos = (PRUEBAS) ? 'turnos_copia_vista' : 'turnos_vista';
		$tablaatenciones = (PRUEBAS) ? 'atenciones_copia' : 'atenciones';
		$idturnoprioritario = $this->db->query("
			SELECT idturno
			FROM $vistaturnos
			WHERE idmodulos = $idmodulo
				AND prioritario = 1
			LIMIT 1"
		);
		if ($idturnoprioritario->num_rows() > 0) {
			foreach ($idturnoprioritario->result() as $resultado) {
				$idturno = $resultado->idturno;
			}
			$this->db->query("
				UPDATE $tablaturnos
				SET idstatus = 2
				WHERE idturno = $idturno"
			);
			$this->db->query("
				INSERT INTO $tablaatenciones(fecha, idturno, idcajas, idmodulo)
				VALUES (CURRENT_TIMESTAMP(), $idturno, $idcaja, $idmodulo)"
			);
			$turno = $this->db->query("
				SELECT turno
				FROM $tablaturnos
				WHERE idturno = $idturno"
			);
			if ($turno->num_rows() > 0) {
				foreach ($turno->result() as $fila) {
					$datos = $fila->turno;
				}
				return 'H' . $datos;
			}
		} else {
			$idturnosinprioridad = $this->db->query("
				SELECT idturno
				FROM $vistaturnos
				WHERE idmodulos = $idmodulo
				LIMIT 1"
			);
			if ($idturnosinprioridad->num_rows() > 0) {
				foreach ($idturnosinprioridad->result() as $resultado) {
					$idturno = $resultado->idturno;
				}
				$this->db->query("
					UPDATE $tablaturnos
					SET idstatus = 2
					WHERE idturno = $idturno"
				);
				$this->db->query("
					INSERT INTO $tablaatenciones(fecha, idturno, idcajas, idmodulo)
					VALUES (CURRENT_TIMESTAMP(), $idturno, $idcaja, $idmodulo)"
				);
				$turno = $this->db->query("
					SELECT turno
					FROM $tablaturnos
					WHERE idturno = $idturno"
				);
				if ($turno->num_rows() > 0) {
					foreach ($turno->result() as $fila) {
						$datos = $fila->turno;
					}
					return $datos;
				}
			}
		}
		return FALSE;
	}
}
