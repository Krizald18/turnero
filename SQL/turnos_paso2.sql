SELECT *
FROM turnos_paso1 tp
WHERE tp.idturno IN (
	SELECT (
		CASE WHEN ((
			SELECT count(0)
			FROM turnos_paso1 tp1
			WHERE tp1.mostrado = 0
				AND tp1.idstatus = 2
				AND tp1.idmodulos = m.idmodulos
		) > 0) THEN (
			SELECT tp1.idturno
			FROM turnos_paso1 tp1
			WHERE tp1.mostrado = 0
				AND tp1.idstatus = 2
				AND tp1.idmodulos = m.idmodulos
			ORDER BY tp1.idturno
			LIMIT 1
		)
		ELSE (
			SELECT tp1.idturno
			FROM turnos_paso1 tp1
			WHERE tp1.mostrado = 1
				AND tp1.idstatus = 2
				AND tp1.idmodulos = m.idmodulos
			ORDER BY tp1.idturno DESC
			LIMIT 1
		)
		END
	)
	FROM modulos m
)
