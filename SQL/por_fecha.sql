SELECT 
	DATE(a.fecha) 'Día',
	COUNT(a.idatenciones) 'Turnos Atendidos'
FROM atenciones a
GROUP BY Día
ORDER BY Día DESC
