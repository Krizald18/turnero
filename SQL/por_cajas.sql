SELECT 
	DATE(a.fecha) 'Día',
	COUNT(a.idatenciones) 'Turnos Atendidos',
	c.caja 'Caja'
FROM atenciones a
INNER JOIN cajas c
	ON c.idcajas = a.idcajas
GROUP BY Día, a.idcajas
ORDER BY Día DESC
