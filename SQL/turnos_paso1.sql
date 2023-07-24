SELECT t.*,	m.modulo,	m.idarea,	c.caja
FROM turnos t
INNER JOIN modulos m 
	ON m.idmodulos = t.idmodulos
INNER JOIN atenciones a
	ON a.idturno = t.idturno
INNER JOIN cajas c
	ON c.idcajas = a.idcajas
WHERE DATE_FORMAT(t.fecha, '%Y-%m-%d') = CURDATE()
LIMIT 200
