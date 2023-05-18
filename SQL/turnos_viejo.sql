SELECT t1.*, m.modulo, m.idarea, c.caja
FROM $turnos t1
INNER JOIN modulos m on t1.idmodulos = m.idmodulos
INNER JOIN atenciones a on a.idturno = t1.idturno
INNER JOIN cajas c on c.idcajas = a.idcajas
WHERE t1.idturno IN (
	SELECT CASE WHEN (
		SELECT count(*)
		FROM $turnos t
		WHERE day(t.fecha) = $d
			AND month(t.fecha) = $m
			AND year(t.fecha) = $y
			AND t.mostrado = 0
			AND t.idstatus = 2
			AND t.idmodulos = m.idmodulos
		) > 0
		THEN (
			SELECT idturno
			FROM $turnos t
			WHERE day(t.fecha) = $d
				AND month(t.fecha) = $m
				AND year(t.fecha) = $y
				AND t.mostrado = 0
				AND t.idstatus = 2
				AND t.idmodulos = m.idmodulos
			ORDER BY fecha ASC
			LIMIT 1
		)
		ELSE (
			SELECT idturno
			FROM $turnos t
			WHERE day(t.fecha) = $d
				AND month(t.fecha) = $m
				AND year(t.fecha) = $y
				AND t.mostrado = 1
				AND t.idstatus = 2
				AND t.idmodulos = m.idmodulos
			ORDER BY fecha DESC
			LIMIT 1
		) END
	FROM modulos m
	WHERE m.idarea = $area
)
ORDER BY t1.turno DESC"
