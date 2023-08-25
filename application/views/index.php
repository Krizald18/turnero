<!DOCTYPE HTML>
<html>
<head>
  <script>
    $(document).ready(function() {
      // Función para ocultar los mensajes
      $("#hide").click(function() {
        $("#mensaje").hide(1000);
      });

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

      function obtenerTurno(idmodulo, strmodulo, botonId, prioritario) {
        fecha = obtenerFecha();
        $.ajax({
          data: {
            idmodulo: idmodulo,
            prioritario: prioritario
          }, // Datos a enviar
          url: '<?= URL ?>index.php/turnos/dar', // A dónde se enviará
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
            prioritario === 1 ?
              $("#resultado").append("<p>H" + data + "</p><br>" + fecha) :
              $("#resultado").append("<p>" + data + "</p><br>" + fecha);
            $(botonId).attr('disabled', null);
            // Manda a imprimir los datos guardados en la div "resultado"
            $("#resultado").printThis({
              debug: false,
              importCSS: true,
              printContainer: true,
              loadCSS: "<?= CSS ?>new-impresion.css",
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

      // Botones Biométricos
      $('#btnBiometricos').click(function() {
        obtenerTurno(17, 'Biométricos', '#btnBiometricos', 0);
      });

      $('#btnBiometricosPrioritario').click(function() {
        obtenerTurno(17, 'Biométricos', '#btnBiometricosPrioritario', 1);
      });

      //  Botones CURP
      $('#btnCurp').click(function() {
        obtenerTurno(1, 'CURP', '#btnCurp', 0);
      });

      $('#btnCurpPrioritario').click(function() {
        obtenerTurno(1, 'CURP', '#btnCurpPrioritario', 1);
      });

      // Botones de Matrimonios y derivados
      $('#btnMatrimonios').click(function() {
        obtenerTurno(3, 'Matrimonios', '#btnMatrimonios', 0);
      });

      $('#btnMatrimoniosPrioritario').click(function() {
        obtenerTurno(3, 'Matrimonios', '#btnMatrimoniosPrioritario', 1);
      });

      $("#btnDomicilio").click(function() {
        let id = 16;
        let modulo = 'Matrimonios a domicilio';
        let prioritario = 0;
        obtenerTurno(id, modulo, "#btnDomicilio", prioritario);
      });

      $("#btnDomicilioPrioritario").click(function() {
        let id = 16;
        let modulo = 'Matrimionios a domicilio';
        let prioritario = 1;
        obtenerTurno(id, modulo, "#btnDomicilioPrioritario", prioritario);
      });

      $("#btnDivorcios").click(function() {
        obtenerTurno(8, 'Divorcios', '#btnDivorcios', 0);
      });

      $("#btnDivorciosPrioritario").click(function() {
        obtenerTurno(8, 'Divorcios', '#btnDivorciosPrioritario', 1);
      });

      // Botones de Aclaraciones
      $("#btnAclaraciones").click(function() {
        obtenerTurno(4, 'Aclaraciones', '#btnAclaraciones', 0);
      });

      $("#btnAclaracionesPrioritario").click(function() {
        obtenerTurno(4, 'Aclaraciones', '#btnAclaracionesPrioritario', 1);
      });

      // Botones del área de Nacimientos (en desuso)
      $("#btnRegistros").click(function() {
        obtenerTurno(5, 'Área de registros', '#btnRegistros', 0);
      });
      $("#btnVenta").click(function() {
        obtenerTurno(9, 'Venta de actas', "#btnVenta", 0);
      });
      $("#btnRectificacion").click(function() {
        obtenerTurno(10, 'Rectificacion de actas', "#btnRectificacion", 0);
      });
      $("#btnInexistencias").click(function() {
        obtenerTurno(11, 'Inexistencias - C.C', "#btnInexistencias", 0);
      });
      $("#btnDivorcioAdmin").click(function() {
        obtenerTurno(13, 'Divorcio Administrativo', "#btnDivorcioAdmin", 0);
      });
      $("#btnNotarios").click(function() {
        obtenerTurno(14, 'Escrituras Públicas', "#btnNotarios", 0);
      });
      $("#btnEspecial").click(function() {
        obtenerTurno(15, 'Caja Especial', "#btnEspecial", 0);
      });
    });
  </script>
</head>

<body class="fondo-gris-oscuro">
  <div class="container" style="max-width: 720px;">
    <div class="panel panel-default sin-bordes sin-margen">
      <div class="panel-heading fondo-negro sin-bordes">
        <h1 class="margen-cabecera">
          Área de <?= $this->user->username ?>
          <span
            class="glyphicon glyphicon-knight"
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
          <strong class="verde"><?= $this->user->username ?></strong> -
          <a href="<?= site_url('welcome/logout')?>" class="rojo">
            Cerrar Sesión
          </a>
        </span>
      </div>
      <div class="panel-body fondo-gris sin-margen">
        <div class="content-main" style="max-width: 80%; margin: 0 auto;">
          <?php if ($this->user->username === 'nacimientos') {?>
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
          <?php } else if ($this->user->username === 'matrimonios') {?>
            <!-- Botones Biométricos -->
            <div class="btn-group-justified" style="margin-bottom: 5px;">
              <div class="btn-group">
                <button
                  type="button"
                  id="btnBiometricos"
                  class="btn btn-primary btn-lg btn-block"
                  style="
                    max-width: 99%;
                    margin-right: 1.1%;">
                  Biométricos
                </button>
              </div>
              <div class="btn-group">
                <button
                  type="button"
                  id="btnBiometricosPrioritario"
                  class="btn btn-primary btn-lg btn-block"
                  style="
                    max-width: 99%;
                    margin-left: 1.1%;">
                  <strong>Prioritario</strong>
                </button>
              </div>
            </div>
            <!-- Botones CURP -->
            <div class="btn-group-justified">
              <div class="btn-group">
                <button
                  type="button"
                  id="btnCurp"
                  class="btn btn-info btn-lg btn-block"
                  style="
                    max-width: 99%;
                    margin-right: 1.1%;">
                  CURP
                </button>
              </div>
              <div class="btn-group">
                <button
                  type="button"
                  id="btnCurpPrioritario"
                  class="btn btn-info btn-lg btn-block"
                  style="
                    max-width: 99%;
                    margin-left: 1.1%;">
                  <strong>Prioritario</strong>
                </button>
              </div>
            </div>
            <!-- Botones Matrimonios y Derivados -->
            <div class="btn-group-justified" style="margin: 5px 0;">
              <div class="btn-group">
                <button
                  type="button"
                  id="btnMatrimonios"
                  class="btn btn-success btn-lg"
                  style="
                    margin-right: 2%;
                    max-width: 98%;">
                  Matrimonios
                </button>
              </div>
              <div class="btn-group">
                <button
                  type="button"
                  id="btnDomicilio"
                  class="btn btn-default btn-lg"
                  style="
                    color: #640;
                    margin-left: 1%;
                    max-width: 98%;">
                  A domicilio
                </button>
              </div>
              <div class="btn-group">
                <button
                  type="button"
                  id="btnDivorcios"
                  class="btn btn-danger btn-lg"
                  style="
                    margin-left: 2%;
                    max-width: 98%;">
                  Divorcios
                </button>
              </div>
            </div>
            <div class="btn-group-justified" style="margin: 5px 0;">
              <div class="btn-group">
                <button
                  type="button"
                  id="btnMatrimoniosPrioritario"
                  class="btn btn-success btn-lg"
                  style="
                    margin-right: 2%;
                    max-width: 98%;">
                  <strong>Prioritario</strong>
                </button>
              </div>
              <div class="btn-group">
                <button
                  type="button"
                  id="btnDomicilioPrioritario"
                  class="btn btn-default btn-lg"
                  style="
                    color: #640;
                    margin-left: 1%;
                    max-width: 98%;">
                  <strong>Prioritario</strong>
                </button>
              </div>
              <div class="btn-group">
                <button
                  type="button"
                  id="btnDivorciosPrioritario"
                  class="btn btn-danger btn-lg"
                  style="
                    margin-left: 2%;
                    max-width: 98%;">
                  <strong>Prioritario</strong>
                </button>
              </div>
            </div>
            <!-- Botones de Aclaraciones -->
            <div class="btn-group-justified">
              <div class="btn-group">
                <button
                  type="button"
                  id="btnAclaraciones"
                  class="btn btn-warning btn-lg btn-block"
                  style="
                    margin-right: 1.1%;
                    max-width: 99%;">
                  Aclaraciones
                </button>
              </div>
              <div class="btn-group">
                <button
                  type="button"
                  id="btnAclaracionesPrioritario"
                  class="btn btn-warning btn-lg btn-block"
                  style="
                    margin-left: 1.1%;
                    max-width: 99%;">
                  <strong>Prioritario</strong>
                </button>
              </div>
            </div>
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
                <?php if ($this->user->username === 'reportes'):?>
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
