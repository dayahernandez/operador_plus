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
							echo "<h4>".$operadores[1]."</h4>
								<h5>".$total[1]." Puntos</h5>";
						?>
					</div>
					<br><br>
					<img class="medalla" src="../../img/segundo.png" alt="">
				</div>
				<div class="col-md-4 text-center" style="background-image: linear-gradient(rgba(0,0,0,0) 1%, rgba(219, 91, 0, 1));">
					<div class="top">
						<?php
							echo "<h4>".$operadores[0]."</h4>
								<h5>".$total[0]." Puntos</h5>";
						?>
					</div>
					<br><br>
					<img class="medalla" src="../../img/primero.png" alt="">
				</div>
				<div class="col-md-4 text-center" style="background-image: linear-gradient(rgba(0,0,0,0) 40%,rgba(62, 88, 204, 1));">
					<div class="top">
						<?php
							echo "<h4>".$operadores[2]."</h4>
								<h5>".$total[2]." Puntos</h5>";
						?>
					</div>
					<br><br>
					<img class="medalla" src="../../img/tercero.png" alt="">
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-md-12">
					<table class="table table-striped ranking" id="ranking" style="font-size: 12px;">
	                    <thead>
	                        <tr>
	                            <th style="background: rgba(255,255,255,1);">Posición</th>
	                            <th style="background: rgba(255,255,255,1);">No. Identificación</th>
	                            <th style="background: rgba(255,255,255,1);">Código TM</th>
	                            <th style="background: rgba(255,255,255,1);">Operador</th>
	                            <th style="background: rgba(255,255,255,1);">Fecha de ingreso</th>
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
	                    		$indice_operador=array_search($_POST['cedula'],$cedulas);
	                    		for($i=0;$i<sizeof($cedulas);$i++){
	                    		//for($i=0;$i<10;$i++){
	                    	?>
									<tr>
										<td class="text-center" style="background: rgba(255,255,255,1);"><?php echo $i+1; ?></td>
										<td class="text-right" style="background: rgba(255,255,255,1);"><?php echo $cedulas[$i] ?></td>
										<td class="text-center" style="background: rgba(255,255,255,1);"><?php echo $codigos[$i] ?></td>
										<td style="background: rgba(255,255,255,1);"><?php echo $operadores[$i] ?></td>
										<td class="text-center" style="background: rgba(255,255,255,1);"><?php echo $fechas_ingreso[$i] ?></td>
										<td class="text-center">
											<?php
												echo $mecanica_total[$cedulas[$i]];
												if($mecanica_total[$cedulas[$i]]=='0'){
													echo '
													<button type="button" title="Ver" onclick="verDetalleHallazgos('.$cedulas[$i].','.$_POST['mes'].','.$_POST['anio'].',\'M\')" class="btn btn-success">
                                            			<span class="fa fa-search"></span>
                                            		</button>';
												}
											?>
										</td>
										<td class="text-center">
											<?php
												echo $carroceria_total[$cedulas[$i]];
												if($carroceria_total[$cedulas[$i]]=='0'){
													echo '
													<button type="button" title="Ver" onclick="verDetalleHallazgos('.$cedulas[$i].','.$_POST['mes'].','.$_POST['anio'].',\'C\')" class="btn btn-success">
	                                        			<span class="fa fa-search"></span>
	                                        		</button>';
												}
											?>
										</td>
										<td class="text-center">
											<?php 
												echo $ascensos_total[$cedulas[$i]];
												if($ascensos_total[$cedulas[$i]]>'0'){
													echo '
													<button type="button" title="Ver" onclick="verValorAgregado('.$cedulas[$i].','.$_POST['mes'].','.$_POST['anio'].',\'A\')" class="btn btn-success">
	                                        			<span class="fa fa-search"></span>
	                                        		</button>';
												}
											?>
										</td>
										<td class="text-center">
											<?php 
												echo $reconocimientos_total[$cedulas[$i]];
											?>
										</td>
										<td class="text-center">
											<?php 
												echo $participacion_total[$cedulas[$i]];
											?>
										</td>
										<td class="text-center">
											<?php 
												echo $vacaciones_total[$cedulas[$i]];
												if($vacaciones_total[$cedulas[$i]]>'0'){
													echo '
													<button type="button" title="Ver" onclick="verValorAgregado('.$cedulas[$i].','.$_POST['mes'].','.$_POST['anio'].',\'V\')" class="btn btn-success">
	                                        			<span class="fa fa-search"></span>
	                                        		</button>';
												}
											?>
										</td>
										<td class="text-center">
											<?php 
												echo $cumplimiento_total[$cedulas[$i]];
											?>
										</td>
										<td class="text-center">
											<?php 
												echo $asistencia_total[$cedulas[$i]];
											?>
										</td>
										<td class="text-center">
											<?php 
												echo $puntualidad_total[$cedulas[$i]];
											?>
										</td>
										<td class="text-center">
											<?php 
												echo $cumplimiento_asis_total[$cedulas[$i]];
											?>
										</td>
										<td class="text-center">
											<?php
												echo $incidentes_total[$cedulas[$i]];
											?>
										</td>
										<td class="text-center">
											<?php
												echo $accidentes_total[$cedulas[$i]];
											?>
										</td>
										<td class="text-center">
											<?php
												echo $percances_total[$cedulas[$i]];
											?>
										</td>
										<td class="text-center"><?php echo $total[$i] ?></td>
									</tr>
	                    	<?php
	                    		}
	                    	?>
                    		<tr id="fila_operador">
								<td class="text-center"  style="background: rgba(144,183,222,1);"><?php echo $indice_operador+1; ?></td>
								<td class="text-right"  style="background: rgba(144,183,222,1);"><?php echo $cedulas[$indice_operador] ?></td>
								<td class="text-center"  style="background: rgba(144,183,222,1);"><?php echo $codigos[$indice_operador] ?></td>
								<td  style="background: rgba(144,183,222,1);"><?php echo $operadores[$indice_operador] ?></td>
								<td class="text-center"  style="background: rgba(144,183,222,1);"><?php echo $fechas_ingreso[$indice_operador] ?></td>
								<td class="text-center">
									<?php
										echo $mecanica_total[$cedulas[$indice_operador]];
										if($mecanica_total[$cedulas[$indice_operador]]=='0'){
											echo '
											<button type="button" title="Ver" onclick="verDetalleHallazgos('.$cedulas[$indice_operador].','.$_POST['mes'].','.$_POST['anio'].',\'M\')" class="btn btn-success">
                                    			<span class="fa fa-search"></span>
                                    		</button>';
										}
									?>
								</td>
								<td class="text-center">
									<?php
										echo $carroceria_total[$cedulas[$indice_operador]];
										if($carroceria_total[$cedulas[$indice_operador]]=='0'){
											echo '
											<button type="button" title="Ver" onclick="verDetalleHallazgos('.$cedulas[$indice_operador].','.$_POST['mes'].','.$_POST['anio'].',\'C\')" class="btn btn-success">
                                    			<span class="fa fa-search"></span>
                                    		</button>';
										}
									?>
								</td>
								<td class="text-center">
									<?php 
										echo $ascensos_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center">
									<?php 
										echo $reconocimientos_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center">
									<?php 
										echo $participacion_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center">
									<?php 
										echo $vacaciones_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center">
									<?php 
										echo $cumplimiento_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center">
									<?php 
										echo $asistencia_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center">
									<?php 
										echo $puntualidad_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center">
									<?php 
										echo $cumplimiento_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center">
									<?php
										echo $incidentes_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center">
									<?php
										echo $accidentes_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center">
									<?php
										echo $percances_total[$cedulas[$indice_operador]];
									?>
								</td>
								<td class="text-center"><?php echo $total[$indice_operador] ?></td>
							</tr>
	                    </tbody>
	                </table>
				</div>
			</div>
		</div>
		<?php include 'Mantenimiento/ModalDetalleMantenimiento.php'; ?>
		<?php include 'ValorAgregado/ModalDetalleValorAgregado.php'; ?>
	</body>
</html>
