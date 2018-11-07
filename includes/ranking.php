<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Operador Plus</title>
		<?php
			include 'head_includes.html';
		?>
		<script src="../../js/general/ranking.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="titulo_h1">
				<img class="logo_centrado_ranking" src="../../img/logo.png" alt=""><br>
				<span class="rojo_masivo">OPERADOR</span> PLUS
			</div>
			<div class="row">
				<div class="col-md-4 text-center" style="background-image: linear-gradient(rgba(0,0,0,0) 20%,rgba(60, 185, 29, 1));">
					<div class="top">
						<?php
							echo "<h4>".$operadores[$indice_segundo]."</h4>
								<h5>".$total[$indice_segundo]." Puntos</h5>";
						?>
					</div>
					<br><br>
					<img class="medalla" src="../../img/segundo.png" alt="">
				</div>
				<div class="col-md-4 text-center" style="background-image: linear-gradient(rgba(0,0,0,0) 1%, rgba(219, 91, 0, 1));">
					<div class="top">
						<?php
							echo "<h4>".$operadores[$indice_primero]."</h4>
								<h5>".$total[$indice_primero]." Puntos</h5>";
						?>
					</div>
					<br><br>
					<img class="medalla" src="../../img/primero.png" alt="">
				</div>
				<div class="col-md-4 text-center" style="background-image: linear-gradient(rgba(0,0,0,0) 40%,rgba(62, 88, 204, 1));">
					<div class="top">
						<?php
							echo "<h4>".$operadores[$indice_tercero]."</h4>
								<h5>".$total[$indice_tercero]." Puntos</h5>";
						?>
					</div>
					<br><br>
					<img class="medalla" src="../../img/tercero.png" alt="">
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-md-12">
					<table width="100%" class="table table-striped ranking" id="ranking">
	                    <thead>
	                        <tr>
	                            <th>Posición</th>
	                            <th>No. Identificación</th>
	                            <th>Código TM</th>
	                            <th>Operador</th>
	                            <th>Fecha de ingreso</th>
	                            <?php 
	                            	while($fila=pg_fetch_assoc($rs_items)){
	                            		echo "<th>".$fila['item_nombre']."</th>";
	                            	}
	                            ?>
	                            <th>Puntaje Total</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    	<?php
	                    		for($i=0;$i<sizeof($cedulas);$i++){
	                    	?>
									<tr>
										<td class="text-center"><?php echo $i+1; ?></td>
										<td class="text-right"><?php echo $cedulas[$i] ?></td>
										<td class="text-center"><?php echo $codigos[$i] ?></td>
										<td><?php echo $operadores[$i] ?></td>
										<td class="text-center"><?php echo $fechas_ingreso[$i] ?></td>
										<td class="text-center">
											<?php
												echo $carroceria_total[$i];
											?>
										</td>
										<td class="text-center">
											<?php
												echo $mecanica_total[$i];
												if($mecanica_total[$i]=='0'){
													echo '
													<button type="button" title="Ver" onclick="verDetalleMecanica('.$cedulas[$i].','.$_POST['mes'].','.$_POST['anio'].')" class="btn btn-success">
                                            			<span  class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            		</button>';
												}
											?>
										</td>
										<td class="text-center"><?php echo $total[$i] ?></td>
									</tr>
	                    	<?php
	                    		}
	                    	?>
	                    </tbody>
	                </table>
				</div>
			</div>
		</div>
	</body>
</html>
