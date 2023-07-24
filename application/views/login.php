<!DOCTYPE HTML>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="<?= CSS ?>nuevo-login.css">
	<script>
		$(document).ready(function() {
			$("#hide").click(function() {
				$("#mensaje").hide(1000);
			});
		});
	</script>
</head>

<body class="fondo-gris-oscuro">
	<div class="container" style="max-width: 720px;">
		<div class="panel panel-default sin-bordes sin-margen">
			<div class="panel-heading fondo-negro sin-bordes sin-margen">
				<h1 class="margen-cabecera">Sistema de turnos</h1>
				<strong class="verde">Registro Civil:</strong>
				Unidad de Servicios Estatales
			</div>
			<div class="panel-body fondo-gris sin-margen">
				<div class="content-main">
					<form method="post" class="form-signin" role="form">
						<h2 class="form-signin-heading">Iniciar Sesión</h2>
						<input
							type="text"
							class="form-control fondo-gris"
							name="username"
							placeholder="Nombre de Usuario"
							required
							autofocus
							value="<?= @$_POST['username'] ?>"
						/>
						<input
							type="password"
							class="form-control fondo-gris"
							name="password"
							placeholder="Contraseña"
							required
							value="<?= @$_POST['password'] ?>"
						/>
						<input
							type="submit"
							class="btn btn-lg btn-danger btn-block"
							style="margin: 15px 0 15px;"
							value="Iniciar sesi&oacute;n"
						/>
						<div id="mensaje" class="alert alert-warning alert-dismissable">
							<strong>Mensaje</strong>
							<?php if (@$error_login): ?>
							<p>Error al iniciar sesión, consulte sus datos.</p>
							<?php endif; ?>
							<?= @validation_errors() ?>
						</div>
					</form>
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
