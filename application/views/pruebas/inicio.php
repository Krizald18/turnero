<script>
  function obtenerFecha() {
    let fecha = new Date();
    anio = fecha.getFullYear().toString();
    mes = (fecha.getMonth() + 1).toString();
    dia = fecha.getDate().toString();
    hora = fecha.getHours().toString();
    min = fecha.getMinutes().toString();
    seg = fecha.getSeconds().toString();
    if (mes.length === 1) mes = '0' + mes;
    if (dia.length === 1) dia = '0' + dia;
    if (hora.length === 1) hora = '0' + hora;
    if (min.length === 1) min = '0' + min;
    if (seg.length === 1) seg = '0' + seg;
    return dia +'-'+ mes +'-'+ anio +' '+ hora +':'+ min +':'+ seg;
  }
</script>
<body>
  <?php 
    echo PRUEBAS ? 
      "<h1>Estamos en PRUEBAS</h1>" :
      "<h1>No estamos en PRUEBAS</h1>";
    echo "<h2>" . date('Y-m-d') . "</h2>";
    echo "<h3>Zona de constantes del sistema</h3>"; 
    echo "<p>" . URL . "</p>";
    echo "<p>" . JS . "</p>";
    echo "<p>" . CSS . "</p>";
    echo "<p>" . IMG . "</p>";
    echo "<p>" . SND . "</p>";
  ?>
  <button>{{obtenerFecha()}}</button>
</body>
</html>
