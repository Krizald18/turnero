<!DOCTYPE html>
<html>
<head>
  <script src="<?= JS ?>angularJS.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?= CSS ?>semantic.min.css">
  <script>
    var API_ROOT = '<?= URL ?>index.php/';
    var app = angular.module('myApp', []);
    var turnos = [];
    var diciendoTurnos = false;
    app.controller('nacCtrl', async function($scope, $http) {
      const decirTurno = async function() {
        if (turnos.length > 0) {
          new Promise(resolve => {
            if (turnos.length > 0) {
              t = turnos.pop();
              var u = new SpeechSynthesisUtterance();
              u.lang = 'es-MX';
              u.rate = 1.2;
              u.text = `turno ${Number(t.turno)} de ${t.modulo} pase a caja ${Number(t.caja)}`;
              u.onend = resolve;
              speechSynthesis.speak(u); // poner ese turno en pantalla
              console.log('deberia decir el turno', t.turno);
              let turnosEnPantalla = [];
              for (var tur of $scope.turnos) {
                if (tur.idturno == t.idturno) {
                  turnosEnPantalla.push(t);
                } else {
                  turnosEnPantalla.push(tur);
                }
              }
              $scope.turnos = turnosEnPantalla;
            }
          }).then( () => {
            if (turnos.length == 0) {
              diciendoTurnos = false;
            } else {
              decirTurno();
            }
          });
        }
      }
      var intervalId = null;

      const checar = function() {
        $http({
          method: 'POST',
          url: API_ROOT + 'pantalla/pantallaTurnos',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data: "idarea=2"
        }).then(response => {
          let noMostrados = response.data.filter(t => Number(t.mostrado) === 0);
          if (noMostrados.length > 0) {
            console.log('turnos pendientes');
            console.log(noMostrados);
            noMostrados =
              noMostrados.filter(
                nm => turnos.filter(
                  tr => nm.idturno == nm.idturno
                ).length === 0
              )
            for (let nm of noMostrados) {
              turnos.push(nm);
            }
            $scope.turnos = response.data;
            if (!diciendoTurnos) {
              diciendoTurnos = true;
              decirTurno();
            }
          } else {
            console.log('se supone que ya los dijo todos')
            $scope.turnos = response.data;
          }
        });
      };
      var intervalId = setInterval(checar, 4000);
    });
    $(document).ready(() => $('body').css({'opacity': '1'}));
  </script>
  <style type="text/css">
    body {
      font-size: 16px;
    }
    #main {
      position: fixed;
      top: 0px;
      left: 0px;
      width: 100%;
      height: 100%;
    }
    #tops {
      height: 6em;
      background: rgba(204, 204, 204, 0.25);
      box-shadow: 0 15px 20px rgba(0, 0, 0, 0.11);
    }
    #rights {
      font-size: 7em;
      /*float: right;*/
      left: 0px;
      height: 100%;
      width: 100%;
    }
    #tbl1 {
      position: fixed;
      left: 5%;
      top: 5%;
      width: 90%;
    }
    td, th {
      padding: unset !important;
      min-width: 3em;
    }
    .cnt {
      text-align: center !important;
    }
    .alertx {
      /*color: black;*/
      background: #21ba45;
    }
    .turnox {
      /*color: white;*/
      background: #c9302c;
    }
    .badge {
      font-size: 3em;
    }
  </style>
</head>
<body ng-app="myApp" ng-controller="nacCtrl" style="opacity: 0">
  <div id="main">
    <section id="rights" ng-if="turnos && turnos.length > 0">
      <table class="ui very basic collapsing celled table" id="tbl1">
      <thead>
        <tr>
          <th style="font-size: 2em"><u>MÓDULO</u></th>
          <th class="cnt" style="font-size: 2em"><u>TURNO</u></th>
          <th class="cnt" style="font-size: 2em"><u>CAJA</u></th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="turno in turnos">
          <th>
            <div style="height: 50px"></div>
            <div>
              <p class='badge' ng-class="turno.mostrado == 0? 'alertx': 'turnox'"><strong>{{turno.modulo}}</strong></p>
            </div>
            <div style="height: 50px"></div>
          </th>
          <th class="cnt">
            <div style="height: 50px"></div>
            <div>
              <p
                class='badge'
                ng-class="turno.mostrado == 0? 'alertx': 'turnox'">
                <strong>
                  {{"000".substring((turno.turno + "").length, 4) +
                  turno.turno}}
                </strong>
              </p>
            </div>
            <div style="height: 50px"></div>
          </th>
          <th class="cnt">
            <div style="height: 50px"></div>
            <div>
              <p
                class='badge'
                ng-class="turno.mostrado == 0? 'alertx' : 'turnox'">
                <strong>
                  {{"00".substring((turno.caja + "").length, 3) + turno.caja}}
                </strong>
              </p>
            </div>
            <div style="height: 50px"></div>
          </th>
        </tr>
      </tbody>
    </table>
    </section>
    <div style="margin: 0;" ng-if="!turnos || (turnos && turnos.length == 0)">
      <div
        style="
          font-size: 10em;
          position: absolute;
          top: 50%;
          left: 42%;
          margin-top: -50px;
          margin-left: -50px;
          width: 100%;">
        No hay turnos
      </div>
    </div>
  </div>
</body>
</html>
