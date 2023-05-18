SELECT
  DATE(t.fecha) 'Día',
  COUNT(t.turno) 'Turnos Atendidos',
  m.modulo 'Módulo'
FROM turnos t
INNER JOIN modulos m
  ON m.idmodulos = t.idmodulos
WHERE t.idstatus = 2
  AND t.mostrado = 1
GROUP BY Día, t.idmodulos
ORDER BY Día DESC, Módulo
