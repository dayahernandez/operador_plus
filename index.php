<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Operador Plus</title>
		<?php
			include 'includes/head.html';
		?>
		<script src="js/general/index.js"></script>
	</head>
	<body>
		<div class="titulo_h1">
			<img class="logo_centrado" src="img/logo.png" alt="">
			<br>
			<span class="rojo_masivo">OPERADOR</span> PLUS
		</div>
		<form action="includes/funciones/ranking_general.php" method="POST">
			<input type="hidden" name="funcion" value="verRanking">
			<div class="row justify-content-md-center">
				<div class="col-md-5 formulario">
					<div class="row justify-content-md-center">
						<div class="col-md-10">
							<label class="amarillo_masivo" for="cedula">No. Cédula</label>
							<input id="cedula" class="form-control" type="number" name="cedula" placeholder="Digita tu número de cédula" required="required" onchange="validarCedula(this.value);">
							<br>
							<div id="alerta_cedula" class="alert alert-warning" role="alert" style="display: none;">
								El número de cédula no coincide con nuestros registros.
							</div>
						</div>
					</div>
					<div class="row justify-content-md-center">
						<div class="col-md-10">
							<label class="amarillo_masivo" for="mes">Mes</label>
							<select id="mes" class="form-control" type="number" name="mes" required="required">
								<option value="1">Enero</option>
								<option value="2">Febrero</option>
								<option value="3">Marzo</option>
								<option value="4">Abril</option>
								<option value="5">Mayo</option>
								<option value="6">Junio</option>
								<option value="7">Julio</option>
								<option value="8">Agosto</option>
								<option value="9">Septiembre</option>
								<option value="10">Octubre</option>
								<option value="11">Noviembre</option>
								<option value="12">Diciembre</option>
							</select>
						</div>
					</div>
					<div class="row justify-content-md-center">
						<div class="col-md-10">
							<label class="amarillo_masivo" for="anio">
								Año
							</label>
							<input id="anio" class="form-control" type="number" name="anio" value="<?php echo date('Y') ?>" step="1" max="<?php echo date('Y') ?>" min='2018' required="required">
						</div>
					</div>
					<br>
					<div class="row justify-content-md-center">
						<div class="col-md-10">
							<button id="consultar" type="submit" class="btn btn-primary btn-block" style="display: none;">Consultar</button>
						</div>
					</div>
					<br>
				</div>
			</div>
		</form>
	</body>
</html>
