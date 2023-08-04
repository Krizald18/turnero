CREATE DEFINER=`root`@`localhost`
PROCEDURE `turno_obtener`(
  IN `prm_id_modulos` INT,
  IN `prm_fecha` char(12),
  IN `prm_id_cajas` INT
)
LANGUAGE SQL
NOT DETERMINISTIC
MODIFIES SQL DATA
SQL SECURITY DEFINER
COMMENT 'obtener turno y actualizar status a 2(atendido) y devolver el turno'
BEGIN
  SET @idturno = (
    SELECT idturno
    FROM turnos
    WHERE idstatus = 1
      AND idmodulos = prm_id_modulos
      AND DATE_FORMAT(fecha, '%Y-%m-%d') = prm_fecha
    LIMIT 1
  );
  
  UPDATE turnos
  SET idstatus = 2
  WHERE idturno = @idturno;
  
  INSERT INTO atenciones(fecha, idturno, idcajas)
  VALUES (CURRENT_TIMESTAMP(), @idturno, prm_id_cajas);
  
  SELECT idturno, turno, idmodulos
  FROM turnos
  WHERE idturno = @idturno;
END
