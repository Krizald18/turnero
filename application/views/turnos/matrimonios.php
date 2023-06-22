<!DOCTYPE html>
<html>
<head>
  <script src="<?= JS ?>angular.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?= CSS ?>nuevo-pantalla.css">
  <script>
  var API_ROOT = '<?= URL; ?>index.php/';
  var app = angular.module('myApp', []);
  var avisar = new Audio('<?= SND;?>ding2.mp3');
  var prioritario = true;
  app.controller('matrCtrl', function($scope, $http) {
    setInterval(function() {
      $http({
        method: 'POST',
        url: API_ROOT + 'pantalla/pantallaTurnos',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        data: "idarea=1"
      }).then(response => {
        $scope.turnos = response.data;
        let pitar = $scope.turnos.filter(
          t => Number(t.mostrado) === 0
        ).length > 0;
        if (pitar) {
          avisar.play();
        }
      });
    }, 6900);
  });
  $(document).ready(() => $('body').css({'opacity': '1'}));
</script>
</head>

<body ng-app="myApp" ng-controller="matrCtrl">
  <table ng-if="turnos && turnos.length > 0">
    <thead>
      <tr>
        <th id="modulos"><u>MÓDULO</u></th>
        <th id="turno"><u>TURNO</u></th>
        <th id="caja"><u>CAJA</u></th>
      </tr>
    </thead>
    <tbody>
      <tr
        ng-repeat="turno in turnos"
        ng-class="turno.mostrado == 0 ? 'alertax' : 'turnox'">
        <td id="modulos" ng-class="turno.mostrado == 0 ? 'alertax' : 'turnox'">
          <strong>{{turno.modulo}}</strong>
        </td>
        <td ng-class="turno.mostrado == 0 ? 'alertax' : 'turnox'">
          <strong>
            {{"000".substring((turno.turno + "").length, 4) +
            turno.turno}}
          </strong>
        </td>
        <td ng-class="turno.mostrado == 0 ? 'alertax' : 'turnox'">
          <strong>
            {{"00".substring((turno.caja + "").length, 3) + turno.caja}}
          </strong>
          <!-- <strong ng-if="prioritario">
            {{"N00".substring((turno.caja + "").length, 3) + turno.caja}}
          </strong> -->
        </td>
      </tr>
    </tbody>
  </table>
  <div ng-if="!turnos || (turnos && turnos.length == 0)">
    <div id="mensaje">
      <!-- <p>No hay turnos</p> -->
      <p>Momentáneamente fuera de servicio<br>En un momento le llamarán</p>
    </div>
  </div>
</body>
</html>
