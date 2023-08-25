<!DOCTYPE html>
<html lang="es">
<head>
  <script src="<?= JS ?>angularJS.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?= CSS ?>new-cajas.css">
  <script>
    let API_ROOT = '<?= URL ?>index.php/';
    let app = angular.module('myApp', []);
    app.controller('myCtrl', function($scope, $http) {
      $scope.areaSeleccionada = null;
      $scope.atendiendo = false;
      $scope.areas = [
        {id: 1, area: 'Matrimonios'},
        {id: 2, area: 'Nacimientos'}
      ];

      // Almacena todas las cajas en la variable 'cajas'
      $http({
        method: 'POST',
        url: API_ROOT + 'cajas/cargarCajas',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: null
      }).then(response => $scope.cajas = response.data);

      // Almacena todos los módulos en la variable modulos
      $http({
        method: 'POST',
        url: API_ROOT + 'cajas/cargarModulos',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: null
      }).then(response => $scope.modulos = response.data);

      /**
       * Al presionar uno de los botones de área, asigna ese valor a la
       * variable seleccionaArea
       */
      $scope.seleccionaArea = area => {
        $scope.areaSeleccionada = area;
      }

      /**
       * Envía la información almacenada en caja.idcaja al presionar un botón
       * de cajas, guarda los módulos asignados a esa caja en la variable
       * listaModulos
       */
      $scope.seleccionaCaja = caja => {
        $scope.caja = caja;
        $http({
          method: 'POST',
          url: API_ROOT + 'cajas/misModulos',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: "idcaja=" + $scope.caja.idcaja
        }).then(response => $scope.listaModulos = response.data);
      }

      /**
       * Para regresar a la página de selección de área, se borra el valor
       * almacenado en areaSeleccionada
       */
      $scope.regresar = () => {
        $scope.areaSeleccionada = null;
      }

      /**
       * Filtra las cajas dependiendo del área seleccionada
       */
      $scope.cajasFiltradas = () =>
        $scope.cajas.filter(
          c => Number(c.idarea) === Number($scope.areaSeleccionada.id)
        );

      /**
       * Obtiene el primer módulo almacenado en la variable modulos.modulo
       */
      $scope.getmodulo = idmodulo =>
        $scope.modulos.filter(m => m.idmodulo === idmodulo)[0].modulo;
      
      $scope.reload = () => window.location.reload();

      $scope.jalarTurno = modulo => {
        setTimeout(() => {
          miturno(modulo.idmodulo, modulo.modulo);
        }, 2000);
      }

      $scope.turnos_atendidos = [];

      $scope.turnos_atendidos_ordenados = () =>
        $scope.turnos_atendidos.sort((a, b) => b - a);

      $scope.turnosPendientes = modulo =>
        !$scope.pendientes || ($scope.pendientes && $scope.pendientes.filter(
          p => Number(p.idmodulo) === Number(modulo.idmodulo)
        ).length === 0);

      let pila = Array();

      /**
       * 
       */
      miturno = (idm, modulo) => {
        let id = $scope.caja.idcaja;
        $.post(
          "<?= site_url('cajas/tomarTurno') ?>",
          {idcaja: id, idmodulo: idm},
          function(data) {
            $scope.atendiendo = true;
            if ($scope.turnos_atendidos.length === 3) {
              $scope.turnos_atendidos.pop();
            }
            $scope.turnos_atendidos.push(data);
            let turno =
              "<h2 class='sin-margen'>" +
                "Turno:</br>" +
                "<span class='label label-danger'>" +
                  data +
                "</span>" +
              "</h2>";
            $('#turnoBox').html('');
            $('#turnoBox').append(
              "<h2 class='sin-margen'>" +
                "Modulo:</br>" +
                "<span class='label label-default'>" +
                  modulo +
                "</span>" +
              "<h2>"
            );
            $('#turnoBox').append(turno);
            setTimeout(() => {
              $scope.atendiendo = false;
            }, 20000);
          }
        );
      }

      setInterval(() => {
        if ($scope.caja) {
          $http({
            method: 'POST',
            url: API_ROOT + 'cajas/misTurnosPendientes',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: "idcaja=" + $scope.caja.idcaja
          }).then(response => $scope.pendientes = response.data);
        }
      }, 500);
    });
    $(document).ready(() => $('body').css({'opacity': '1'}));
  </script>
</head>

<body ng-app="myApp" ng-controller="myCtrl">
  <div class="container" style="max-width: 720px;" ng-if="!caja">
    <div class="panel panel-default sin-bordes sin-margen">
      <header class="panel-heading sin-bordes">
        <h1 class="margen-cabecera">
          Selecciona tu {{areaSeleccionada? 'caja' : 'área'}}
          <button
            ng-if="areaSeleccionada"
            type="button"
            class="btn btn-danger"
            style="float: right;"
            ng-click="regresar()">
            <span class="glyphicon glyphicon-arrow-left"></span> Regresar
          </button>
        </h1>
      </header>
      <main class="panel-body" ng-if="!areaSeleccionada">
        <div class="div-seleccion">
          <button
            type="button"
            class="btn btn-primary btn-areas btn-block"
            ng-repeat="area in areas"
            ng-click="seleccionaArea(area)">
            {{area.area}}
          </button>
        </div>
      </main>
      <main class="panel-body" ng-if="areaSeleccionada">
        <div class="div-seleccion">
          <button
            type="button"
            class="btn btn-info btn-cajas"
            ng-repeat="caja in cajasFiltradas()"
            ng-click="seleccionaCaja(caja)">
            {{caja.caja}}
          </button>
        </div>
      </main>
      <footer class="panel-footer sin-bordes">
        La página se cargó en 
        <strong class="rojo">{elapsed_time}</strong> segundos.
      </footer>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default sin-bordes sin-margen" ng-if="caja">
      <div class="panel-heading sin-bordes">
        <h1 class="margen-cabecera">
          Sistema de turnos: Registro Civil
          <span class="glyphicon glyphicon-knight gris"></span>
          <small style="float: right;">
            <button type="button" class="btn btn-danger" ng-click="reload()">
              Cerrar Sesión
            </button>
          </small>
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
            class="progress-bar"
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
        <p class="sin-margen">
          Bienvenido a la caja <strong class="verde">{{caja.caja}}</strong>
        </p>
      </div>
      <div class="panel-body">
        <div class="col-md-3">
          <div class="panel panel-success panel-grande">
            <div class="panel-heading">
              <h3 class="panel-title">Llamar un nuevo turno</h3>
            </div>
            <div class="panel-body">
              <ul class="list-group sin-margen">
                <button
                  type='button'
                  ng-id="modulo.idmodulo"
                  ng-repeat="modulo in listaModulos"
                  ng-click='jalarTurno(modulo)'
                  class='btn btn-lg btn-block'
                  ng-class="turnosPendientes(modulo) ?
                    btn-default : 'btn-success'"
                  value='modulo.idmodulo'
                  ng-disabled="turnosPendientes(modulo)">
                  {{modulo.modulo}}
                </button>
              </ul>
              <ul ng-if="listaModulos.length === 0">
                <span class='badge' style="background-color: #d9534f">
                  SIN MODULOS ASIGNADOS
                </span>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="panel panel-default panel-grande">
            <div class="panel-heading">
              <h3 class="panel-title">Turno actual</h3>
            </div>
            <div class="panel-body sin-margen">
              <div
                id="turnoBox"
                class="jumbotron sin-margen"
                ng-if="pendientes.length > 0 || atendiendo">
              </div>
              <div
                id="turnoBox"
                class="jumbotron sin-margen"
                ng-if="pendientes.length == 0 && !atendiendo">
                <h2 class="sin-margen">
                  Turno:<br>
                  <span class='label label-danger'>Sin turnos pendientes</span>
                </h2>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-danger panel-arriba">
            <div class="panel-heading correccion">
              <h3 class="panel-title">Turnos pendientes</h3>
            </div>
            <div class="panel-body" id="divturnospndientes">
                <button
                  type="button"
                  ng-repeat="pendiente in pendientes"
                  class="btn btn-block btn-lg btn-danger sin-padding">
                  {{pendiente.pendientes}} de
                  {{getmodulo(pendiente.idmodulo).toLowerCase()}}
                </button>
              <div>
                <button
                  type="button"
                  ng-if="pendientes.length === 0"
                  class="btn btn-block btn-lg btn-danger disabled">
                  No hay pendientes
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-danger panel-abajo">
            <div class="panel-heading correccion">
              <h3 class="panel-title">Turnos atendidos</h3>
            </div>
            <div class="panel-body" id="divturnosllamados">
              <div ng-repeat="ultimo_turno in turnos_atendidos_ordenados()">
                <button
                  type="button"
                  class='btn btn-danger disabled sin-pad-vertical'
                  ng-if="$index === 0">
                  {{ultimo_turno + ' (Último turno)'}}
                </button>
                <button
                  type="button"
                  class='btn btn-danger disabled sin-pad-vertical'
                  ng-if="$index != 0">
                  {{ultimo_turno}}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-footer sin-bordes">
        <p class="footer sin-margen">
          La página se cargó en <strong class="rojo">{elapsed_time}</strong>
          segundos.
        </p>
      </div>
    </div>
  </div>
</body>
</html>
