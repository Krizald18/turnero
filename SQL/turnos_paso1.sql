SELECT t.*, a.idatenciones, a.fecha, m.modulo, m.idarea, c.caja
FROM turnos t
JOIN modulos m ON (m.idmodulos = t.idmodulos)
JOIN atenciones a ON (a.idturno = t.idturno)
JOIN cajas c ON (c.idcajas = a.idcajas)
WHERE date_format(t.fecha,'%Y-%m-%d') = curdate()
LIMIT 200
