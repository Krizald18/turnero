<!DOCTYPE HTML>
<html>
<head>
  <script type="text/javascript" src="<?= JS?>printThis.js"></script>
  <script>
    $(document).ready(function() {
      // Función para ocultar los mensajes
      $("#hide").click(function() {
        $("#mensaje").hide(1000);
      });

      function obtenerFecha() {
        var fecha = new Date();
        anio = fecha.getFullYear().toString();
        mes = (fecha.getMonth() + 1).toString();
        dia = fecha.getDate().toString();
        hora = fecha.getHours().toString();
        min = fecha.getMinutes().toString();
        seg = fecha.getSeconds().toString();
        if (mes.length == 1) mes = '0' + mes;
        if (dia.length == 1) dia = '0' + dia;
        if (hora.length == 1) hora = '0' + hora;
        if (min.length == 1) min = '0' + min;
        if (seg.length == 1) seg = '0' + seg;
        return dia +'-'+ mes +'-'+ anio +' '+ hora +':'+ min +':'+ seg;
      }

      function obtenerTurno(idmodulo, strmodulo, botonId) {
        fecha = obtenerFecha();
        $.ajax({
          data: {idmodulo: idmodulo}, // Valor a enviar
          url: '<?= URL?>/index.php/turnos/dar', // A dónde se enviará
          type: 'POST', // Método para enviar los datos
          cache: false, // No queremos que los datos se guarden en caché
          // Antes de enviar los datos:
          beforeSend: function() {
            $(botonId).attr('disabled', true);
            $("#resultado").html("Procesando, espere un momento...");
          },
          // Si el envío de datos es exitoso:
          success: function(data) {
            $("#mensaje").show(1000); // Se muestra el cuadro de texto
            // Se muestra la información en pantalla del turno nuevo
            $("#resultado").html('');
            $("#resultado").append('<h2>' + strmodulo + '</h2>');
            $("#resultado").append('<p>' + data + '</p>' + "<br>" + fecha);
            $(botonId).attr('disabled', null);
            // Manda a imprimir los datos guardados en la div "resultado"
            $("#resultado").printThis({
              debug: false,
              importCSS: true,
              printContainer: true,
              loadCSS: "<?= CSS?>new-impresion.css",
              pageTitle: "",
              removeInline: true,
              printDelay: 333,
              header: null
            });
          },
          error: function(dato) {
            $("resultado").html("Ha ocurrido el siguente error: " + dato);
          }
        });
      }
      $('#btnCurp').click(function() {
        var id = 1;
        var modulo = 'CURP';
        obtenerTurno(id, modulo, '#btnCurp');
      });
      $('#btnBiometricos').click(function() {
        var id = 17;
        var modulo = 'Biométricos';
        obtenerTurno(id, modulo, '#btnBiometricos');
      });
      $('#btnMatrimonios').click(function() {
        var id = 3;
        var modulo = 'Matrimonios';
        obtenerTurno(id, modulo, '#btnMatrimonios');
      });
      $("#btnAclaraciones").click(function() {
        obtenerTurno(4, 'Aclaraciones', '#btnAclaraciones');
      });
      $("#btnRegistros").click(function() {
        obtenerTurno(5, 'Área de registros', "#btnRegistros");
      });
      $("#btnDivorcios").click(function() {
        var id = 8;
        var modulo = 'Divorcios';
        obtenerTurno(id, modulo, '#btnDivorcios');
      });
      $("#btnVenta").click(function() {
        obtenerTurno(9, 'Venta de actas', "#btnVenta");
      });
      $("#btnRectificacion").click(function() {
        obtenerTurno(10, 'Rectificacion de actas', "#btnRectificacion");
      });
      $("#btnInexistencias").click(function() {
        obtenerTurno(11, 'Inexistencias - C.C', "#btnInexistencias");
      });
      $("#btnDivorcioAdmin").click(function() {
        obtenerTurno(13, 'Divorcio Administrativo', "#btnDivorcioAdmin");
      });
      $("#btnNotarios").click(function() {
        obtenerTurno(14, 'Escrituras Públicas', "#btnNotarios");
      });
      $("#btnEspecial").click(function() {
        obtenerTurno(15, 'Caja Especial', "#btnEspecial");
      });
      $("#btnDomicilio").click(function() {
        var id = 16;
        var modulo = 'Matrimionios a domicilio';
        obtenerTurno(id, modulo, "#btnDomicilio");
      });
    });
  </script>
</head>

<body class="fondo-gris-oscuro">
  <div class="container">
    <div class="panel panel-default sin-bordes sin-margen">
      <div class="panel-heading fondo-negro sin-bordes">
        <h1 class="margen-cabecera">
          Área de <?= $this->user->username ?>
          <span
            class="glyphicon glyphicon-hd-video"
            style="color: gray;">
          </span>
        </h1>
        <div
          class="progress progress-striped active"
          style="margin-bottom: 10px;">
          <div
            class="progress-bar progress-bar-success"
            role="progressbar"
            aria-valuenow="25"
            aria-valuemin="0"
            aria-valuemax="100"
            style="width: 25%">
            <span class="sr-only"></span>
          </div>
          <div
            class="progress-bar progress-bar-info"
            role="progressbar"
            aria-valuenow="40"
            aria-valuemin="0"
            aria-valuemax="100"
            style="width: 50%; background-color: #eee;">
            <span class="sr-only"></span>
          </div>
          <div
            class="progress-bar progress-bar-danger"
            role="progressbar"
            aria-valuenow="25"
            aria-valuemin="0"
            aria-valuemax="100"
            style="width: 25%">
            <span class="sr-only"></span>
          </div>
        </div>
        <span>
          Bienvenido a 
          <strong class="verde"><?= $this->user->username?></strong> -
          <a href="<?= site_url('welcome/logout')?>" class="rojo">
            Cerrar Sesión
          </a>
        </span>
      </div>
      <div class="panel-body fondo-gris sin-margen">
        <div class="content-main" style="max-width:420px; margin: 0 auto;">
          <?php if ($this->user->username == 'nacimientos') {?>
            <button
              type="button"
              id="btnRegistros"
              class="btn btn-success btn-lg btn-block">
              Área de registros
            </button>
            <button
              type="button"
              id="btnVenta"
              class="btn btn-default btn-lg btn-block">
              Venta de actas
            </button>
            <button
              type="button"
              id="btnRectificacion"
              class="btn btn-danger btn-lg btn-block">
              Rectificación de actas
            </button>
            <button
              type="button"
              id="btnEspecial"
              class="btn btn-primary btn-lg btn-block">
              Caja Especial
            </button>
          <?php } else if ($this->user->username == 'matrimonios') {?>
            <button
              type="button"
              id="btnCurp"
              class="btn btn-primary btn-lg btn-block"
              style="margin: 0 0 5px 0;">
              CURP
            </button>
            <button
              type="button"
              id="btnBiometricos"
              class="btn btn-info btn-lg btn-block"
              style="margin: 0 0 5px 0;">
              Biométricos
            </button>
            <div class="btn-group-justified" style="margin: 5px 0;">
              <div class="btn-group">
                <button
                  type="button"
                  id="btnMatrimonios"
                  class="btn btn-success btn-lg"
                  style="
                    margin-right: 1.1%;
                    max-width: 99%;">
                  Matrimonios
                </button>
              </div>
              <!-- Botón de Matrimonios a Domicilio -->
              <div class="btn-group">
                <button
                  type="button"
                  id="btnDomicilio"
                  class="btn btn-default btn-lg"
                  style="
                    color: #640;
                    margin-left: 0.5%;
                    max-width: 99%;">
                  A domicilio
                </button>
              </div>
              <div class="btn-group">
                <button
                  type="button"
                  id="btnDivorcios"
                  class="btn btn-danger btn-lg"
                  style="
                    margin-left: 1.1%;
                    max-width: 99%;">
                  Divorcios
                </button>
              </div>
            </div>
            <button
              type="button"
              id="btnAclaraciones"
              class="btn btn-warning btn-lg btn-block"
              style="margin: 5px 0 0 0;">
              Aclaraciones
            </button>
          <?php } else {?>
            <button
              type="button"
              id=""
              class="btn btn-success btn-lg btn-block">
              Por Fecha
            </button><br/>
            <button
              type="button"
              id=""
              class="btn btn-default btn-lg btn-block"
              style="color: #640;">
              Por Módulo
            </button><br/>
            <button
              type="button"
              id=""
              class="btn btn-danger btn-lg btn-block">
              Por Caja
            </button>
          <?php }?>
          <br>
          <div
            id="mensaje"
            class="alert alert-warning sin-margen">
            <strong>
              <span>
                <?php if ($this->user->username == 'reportes'):?>
                  Información del reporte generado
                <?php else: ?>
                  Información del turno entregado
                <?php endif; ?>
              </span>
            </strong>
            <div id='resultado'></div>
          </div>
        </div>
      </div>
      <div class="panel-footer fondo-negro sin-bordes">
        <p class="footer sin-margen">
          La página se cargó en
          <strong class="rojo">{elapsed_time}</strong> segundos
        </p>
      </div>
    </div>
  </div>
</body>
</html>
