<?php
	if(!isset($_POST['funcion'])){
		header("Location: ../../index.php");
		die();
	}
	include '../conexion.php';
	echo $_POST['funcion']();
	function verRanking(){
		/////////////////////////// Operadores ///////////////////////////
		$consulta="select * from sw_oplus_items order by item_id";
		$rs_items=ejecutarSql($consulta);
		$consulta="select e.yaan8,em.emp_fecha_ingreso,trim(e.yaalph) as operador,
			trim(e.yaoemp) as codigo,trim(e.yassn) as identificacion,e.yamcu
			from sw_maestro_empleados e
			inner join sw_empleados em on(cast(e.yaan8 as text)=trim(em.emp_an8))
			inner join sw_udc u on (trim(u.drsy)='06' and trim(u.drrt)='G' and trim(e.yajbcd)=trim(u.drky))
			inner join sw_disciplinario_oficinas o on(trim(e.yamcu)=o.ofi_udc)
			where e.yapast='0' and u.drdl01 like '%OPERADOR%'  and trim(e.yaoemp)<>'' and 1=1 
			group by e.yaan8,em.emp_fecha_ingreso order by 2";
		$rs_operadores=ejecutarSql($consulta);
		$cedulas=array();
		$codigos=array();
		$operadores=array();
		$fechas_ingreso=array();
		$total=array();
		while($fila=pg_fetch_assoc($rs_operadores)){
			array_push($cedulas,$fila['identificacion']);
			array_push($codigos,$fila['codigo']);
			array_push($operadores,$fila['operador']);
			array_push($fechas_ingreso,$fila['emp_fecha_ingreso']);
			array_push($total,0);
		}
		/////////////////////////// Mantenimiento  ///////////////////////////
		$consulta="select trim(e.yassn) as identificacion,
				(select count(m.man_id) from sw_oplus_mantenimiento m where cast(m.man_codigo as text)=trim(e.yaoemp)
				and extract(month from m.man_fecha)=".$_POST['mes']." and extract(year from m.man_fecha)=".$_POST['anio']." and m.man_tipo='M' group by m.man_codigo)as mecanica,
				(select count(m.man_id) from sw_oplus_mantenimiento m where cast(m.man_codigo as text)=trim(e.yaoemp)
				and extract(month from m.man_fecha)=".$_POST['mes']." and extract(year from m.man_fecha)=".$_POST['anio']." and m.man_tipo='C' group by m.man_codigo)as carroceria
				from sw_maestro_empleados e
				inner join sw_empleados em on(cast(e.yaan8 as text)=trim(em.emp_an8))
				inner join sw_udc u on (trim(u.drsy)='06' and trim(u.drrt)='G' and trim(e.yajbcd)=trim(u.drky))
				inner join sw_disciplinario_oficinas o on(trim(e.yamcu)=o.ofi_udc)
				left join sw_oplus_mantenimiento m on(trim(yaoemp)=cast(m.man_codigo as text) 
				and extract(month from man_fecha)=".$_POST['mes']." and extract(year from man_fecha)=".$_POST['anio']." and m.man_tipo='M')
				where e.yapast='0' and u.drdl01 like '%OPERADOR%'  and trim(e.yaoemp)<>'' and 1=1  
				group by e.yaan8,em.emp_fecha_ingreso order by 2";
		$rs_mecanica=ejecutarSql($consulta);
		$mecanica_total=array();
		$carroceria_total=array();
		while($fila=pg_fetch_assoc($rs_mecanica)){
			$indice=array_search($fila['identificacion'],$cedulas);
			$consulta="select item_puntaje from sw_oplus_items where item_clave='mecanica'";
			$rs_puntaje_mecanica=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_mecanica)){
				if($fila['mecanica']==0){
					$mecanica_total[$fila['identificacion']]=$fila_puntaje['item_puntaje'];
				}else{					
					$mecanica_total[$fila['identificacion']]=0;
				}
			}
			$consulta="select item_puntaje from sw_oplus_items where item_clave='carroceria'";
			$rs_puntaje_mecanica=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_mecanica)){
				if($fila['carroceria']==0){
					$carroceria_total[$fila['identificacion']]=$fila_puntaje['item_puntaje'];
				}else{					
					$carroceria_total[$fila['identificacion']]=0;
				}
			}
			$total[$indice]+=$mecanica_total[$fila['identificacion']]+$carroceria_total[$fila['identificacion']];
		}
		/////////////////////////// Valor Agregado ///////////////////////////
		$consulta="select e.yaan8,em.emp_fecha_ingreso,trim(e.yaalph) as operador,trim(e.yaoemp) as codigo,trim(e.yassn) as identificacion,e.yamcu,
				(select count(v.val_id) from sw_oplus_valagregado v where cast(v.val_codigo as text)=trim(e.yaoemp) 
				and extract(month from v.val_fecha)=".$_POST['mes']." and extract(year from v.val_fecha)=".$_POST['anio']." 
				and v.val_tipo='A' group by v.val_codigo) as ascensos,
				(select count(v.val_id) from sw_oplus_valagregado v where cast(v.val_codigo as text)=trim(e.yaoemp) 
				and extract(month from v.val_fecha)=".$_POST['mes']." and extract(year from v.val_fecha)=".$_POST['anio']." 
				and v.val_tipo='R' group by v.val_codigo) as reconocimientos,
				(select count(v.val_id) from sw_oplus_valagregado v where cast(v.val_codigo as text)=trim(e.yaoemp) 
				and extract(month from v.val_fecha)=".$_POST['mes']." and extract(year from v.val_fecha)=".$_POST['anio']." 
				and v.val_tipo='P' group by v.val_codigo) as participacion,
				(select count(v.val_id) from sw_oplus_valagregado v where cast(v.val_codigo as text)=trim(e.yaoemp) 
				and extract(month from v.val_fecha)=".$_POST['mes']." and extract(year from v.val_fecha)=".$_POST['anio']." 
				and v.val_tipo='C' group by v.val_codigo) as cumplimiento,
				(select v.val_dias from sw_oplus_valagregado v where cast(v.val_codigo as text)=trim(e.yaoemp) 
				and extract(month from v.val_fecha)=".$_POST['mes']." and extract(year from v.val_fecha)=".$_POST['anio']." 
				and v.val_tipo='V' group by v.val_dias) as vacaciones
				from sw_maestro_empleados e
				inner join sw_empleados em on(cast(e.yaan8 as text)=trim(em.emp_an8))
				inner join sw_udc u on (trim(u.drsy)='06' and trim(u.drrt)='G' and trim(e.yajbcd)=trim(u.drky))
				inner join sw_disciplinario_oficinas o on(trim(e.yamcu)=o.ofi_udc)
				left join sw_oplus_valagregado v on(trim(yaoemp)=cast(v.val_codigo as text) 
				and extract(month from val_fecha)=".$_POST['mes']." and extract(year from val_fecha)=".$_POST['anio'].")
				where e.yapast='0' and u.drdl01 like '%OPERADOR%' and trim(e.yaoemp)<>'' group by e.yaan8,em.emp_fecha_ingreso order by 2";
		$ascensos_total=array();
		$participacion_total=array();
		$reconocimientos_total=array();
		$vacaciones_total=array();
		$cumplimiento_total=array();
		$rs_valagregado=ejecutarSql($consulta);
		while ($fila=pg_fetch_assoc($rs_valagregado)) {
			$indice=array_search($fila['identificacion'],$cedulas);
			$consulta="select item_puntaje from sw_oplus_items where item_clave='ascenso'";
			$rs_puntaje_ascensos=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_ascensos)){
				$ascensos_total[$fila['identificacion']]=$fila['ascensos']*$fila_puntaje['item_puntaje'];
			}

			$consulta="select item_puntaje from sw_oplus_items where item_clave='reconocimiento'";
			$rs_puntaje_reconocimiento=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_reconocimiento)){
				$reconocimientos_total[$fila['identificacion']]=$fila['reconocimientos']*$fila_puntaje['item_puntaje'];
			}

			$consulta="select item_puntaje from sw_oplus_items where item_clave='participacion'";
			$rs_puntaje_participacion=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_participacion)){
				$participacion_total[$fila['identificacion']]=$fila['participacion']*$fila_puntaje['item_puntaje'];
			}

			$consulta="select item_puntaje from sw_oplus_items where item_clave='vacaciones'";
			$rs_puntaje_vacaciones=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_vacaciones)){
				$vacaciones_total[$fila['identificacion']]=$fila['vacaciones']*$fila_puntaje['item_puntaje'];
			}

			$consulta="select item_puntaje from sw_oplus_items where item_clave='cumplimiento'";
			$rs_puntaje_cumplimiento=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_cumplimiento)){
				$cumplimiento_total[$fila['identificacion']]=$fila['cumplimiento']*$fila_puntaje['item_puntaje'];
			}
			$total[$indice]+=$ascensos_total[$fila['identificacion']]+$reconocimientos_total[$fila['identificacion']]+
			$participacion_total[$fila['identificacion']]+$vacaciones_total[$fila['identificacion']]+$cumplimiento_total[$fila['identificacion']];
		}
		/////////////////////////// Asistencia al trabajo ///////////////////////////
		$consulta="select trim(e.yaoemp) as codigo, trim(e.yassn) as identificacion, trim(e.yaalph) as operador, em.emp_fecha_ingreso,
				(SELECT COUNT(asis_falta)  FROM sw_oplus_asistencia a WHERE  asis_cedula=(trim(yassn)) and asis_falta = TRUE 
						and asis_mes = '".$_POST['mes']."' and asis_año='".$_POST['anio']."') as asis_falta,
				(SELECT COUNT(asis_puntualidad) asis_puntualidad FROM sw_oplus_asistencia WHERE asis_cedula=(trim(yassn)) and asis_puntualidad = TRUE
						and asis_mes = '".$_POST['mes']."' and asis_año='".$_POST['anio']."') as asis_puntualidad,
				(SELECT COUNT(asis_cumplimiento) asis_cumplimiento FROM sw_oplus_asistencia WHERE asis_cedula=(trim(yassn)) and asis_cumplimiento = TRUE
						and asis_mes = '".$_POST['mes']."' and asis_año='".$_POST['anio']."') as asis_cumplimiento
				from sw_maestro_empleados e 
				inner join sw_empleados em on(cast(e.yaan8 as text)=trim(em.emp_an8))
				inner join sw_udc u on (trim(u.drsy)='06' and trim(u.drrt)='G' and trim(e.yajbcd)=trim(u.drky))
				inner join sw_disciplinario_oficinas o on(trim(e.yamcu)=o.ofi_udc)
				left join sw_oplus_asistencia a on (trim(e.yassn)=trim(a.asis_cedula) and asis_mes = '".$_POST['mes']."' and asis_año='".$_POST['anio']."')
				where e.yapast='0' and drdl01 like '%OPERADOR%' and trim(e.yaoemp)<>''
				group by e.yassn,e.yaoemp,e.yaalph,e.yadst,em.emp_fecha_ingreso
				order by 4";
		$rs_asistencia=ejecutarSql($consulta);
		$asistencia_total=array();
		$puntualidad_total=array();
		$cumplimiento_asis_total=array();
		while ($fila=pg_fetch_assoc($rs_asistencia)) {
			$indice=array_search($fila['identificacion'],$cedulas);
			$consulta="select item_puntaje from sw_oplus_items where item_clave='asistencia'";
			$rs_puntaje_asistencia=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_asistencia)){
				$asistencia_total[$fila['identificacion']]=$fila['asis_falta']*$fila_puntaje['item_puntaje'];
			}
			$consulta="select item_puntaje from sw_oplus_items where item_clave='puntualidad'";
			$rs_puntaje_puntualidad=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_puntualidad)){
				$puntualidad_total[$fila['identificacion']]=$fila['asis_puntualidad']*$fila_puntaje['item_puntaje'];
			}
			$consulta="select item_puntaje from sw_oplus_items where item_clave='cumplimiento_asis'";
			$rs_puntaje_cumplimiento=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_cumplimiento)){
				$cumplimiento_asis_total[$fila['identificacion']]=$fila['asis_cumplimiento']*$fila_puntaje['item_puntaje'];
			}
			$total[$indice]+=$asistencia_total[$fila['identificacion']]+$puntualidad_total[$fila['identificacion']]+$cumplimiento_asis_total[$fila['identificacion']];
		}
		/////////////////////////// Seguridad vial ///////////////////////////
		$consulta="select trim(me.yaalph) as operador,me.yaan8,trim(me.yaoemp) as codigo, trim(me.yassn) as identificacion,em.emp_fecha_ingreso,
					(select count(ct.cas_afectacion) from sw_accidentalidad_vial_casos_terceros ct 
					where  ct.yaan8=me.yaan8 and extract(month from cas_fcaccidente)='".$_POST['mes']."' and extract(year from cas_fcaccidente)='".$_POST['anio']."' and ct.cas_afectacion='Accidente' group by ct.yaan8) as accidente_t,
					(select count(ct.cas_afectacion) from sw_accidentalidad_vial_casos_terceros ct 
					where ct.yaan8=me.yaan8 and extract(month from cas_fcaccidente)='".$_POST['mes']."' and extract(year from cas_fcaccidente)='".$_POST['anio']."' and ct.cas_afectacion='Incidente' group by ct.yaan8) as incidente_t,
					(select count(ct.cas_afectacion) from sw_accidentalidad_vial_casos_terceros ct 
					where ct.yaan8=me.yaan8 and extract(month from cas_fcaccidente)='".$_POST['mes']."' and extract(year from cas_fcaccidente)='".$_POST['anio']."' and ct.cas_afectacion='Percance' group by ct.yaan8) as percance_t,
					(select  count(c.cas_afectacion) from sw_accidentalidad_vial_casos c inner join sw_accidentalidad_datos_operador op on(c.cas_id=op.cas_id) 
					where op.yaan8=me.yaan8 and extract(month from cas_fcaccidente)='".$_POST['mes']."' and extract(year from cas_fcaccidente)='".$_POST['anio']."' and c.cas_afectacion='Accidente' group by op.yaan8) as accidente,
					(select  count(c.cas_afectacion) from sw_accidentalidad_vial_casos c inner join sw_accidentalidad_datos_operador op on(c.cas_id=op.cas_id) 
					where op.yaan8=me.yaan8 and extract(month from cas_fcaccidente)='".$_POST['mes']."' and extract(year from cas_fcaccidente)='".$_POST['anio']."' and c.cas_afectacion='Incidente' group by op.yaan8) as incidente,
					(select  count(c.cas_afectacion) from sw_accidentalidad_vial_casos c inner join sw_accidentalidad_datos_operador op on(c.cas_id=op.cas_id) 
					where op.yaan8=me.yaan8 and extract(month from cas_fcaccidente)='".$_POST['mes']."' and extract(year from cas_fcaccidente)='".$_POST['anio']."' and c.cas_afectacion='Percance' group by op.yaan8) as percance
					from sw_maestro_empleados me
					inner join sw_empleados em on(em.emp_an8::numeric=me.yaan8)
					inner join sw_udc ca on (trim(drsy)='06' and trim(drrt)='G' and trim(yajbcd)=trim(drky))
					inner join sw_disciplinario_oficinas o on(trim(me.yamcu)=o.ofi_udc)
					where me.yapast='0' and ca.drdl01 like '%OPERADOR%' and trim(me.yaoemp)<>''
					GROUP BY me.yaan8,em.emp_fecha_ingreso ORDER BY 5";
		$rs_seguridadVial=ejecutarSql($consulta);
		$accidentes_total=array();
		$incidentes_total=array();
		$percances_total=array();
		while ($fila=pg_fetch_assoc($rs_seguridadVial)) {
			$indice=array_search($fila['identificacion'],$cedulas);
			if($fila['accidente_t']>0 OR $fila['accidente']>0){
				$accidentes_total[$fila['identificacion']]=0;
			}else{
				$consulta="select item_puntaje from sw_oplus_items where item_clave='accidentes'";
				$rs_puntaje_accidentes=ejecutarSql($consulta);
				if($fila_puntaje=pg_fetch_assoc($rs_puntaje_accidentes)){
					$accidentes_total[$fila['identificacion']]=$fila_puntaje['item_puntaje'];
				}
			}
			if($fila['incidente_t']>0 OR $fila['incidente']>0){
				$incidentes_total[$fila['identificacion']]=0;
			}else{
				$consulta="select item_puntaje from sw_oplus_items where item_clave='incidentes'";
				$rs_puntaje_incidentes=ejecutarSql($consulta);
				if($fila_puntaje=pg_fetch_assoc($rs_puntaje_incidentes)){
					$incidentes_total[$fila['identificacion']]=$fila_puntaje['item_puntaje'];
				}
			}
			if($fila['percance_t']>0 OR $fila['percance']>0){
				$percances_total[$fila['identificacion']]=0;
			}else{
				$consulta="select item_puntaje from sw_oplus_items where item_clave='percances'";
				$rs_puntaje_percances=ejecutarSql($consulta);
				if($fila_puntaje=pg_fetch_assoc($rs_puntaje_percances)){
					$percances_total[$fila['identificacion']]=$fila_puntaje['item_puntaje'];
				}
			}
			$total[$indice]+=$accidentes_total[$fila['identificacion']]+$incidentes_total[$fila['identificacion']]+$percances_total[$fila['identificacion']];
		}
		array_multisort($total,SORT_DESC,$fechas_ingreso,SORT_ASC,$operadores,SORT_ASC,$codigos,$cedulas);				
		include '../ranking.php';

	}
	function validarCedula(){
		$consulta="select * from sw_empleados where emp_identificacion='".$_POST['cedula']."'";
		$rs=ejecutarSql($consulta);
		if(pg_num_rows($rs)>0){
			if($fila=pg_fetch_assoc($rs)){
				return json_encode($fila);
			}
		}else{
			return json_encode("false");
		}
	}
	function verDetalleHallazgos(){
		$consulta="select m.*,trim(e.yaalph) as operador,(case man_tipo when 'M' then 'Hallazgo de mantenimiento' when 'C' then 'Hallazgo en la carrocería' end)as tipo_hallazgo
					from sw_oplus_mantenimiento m
					inner join sw_maestro_empleados e on(trim(yaoemp)=cast(m.man_codigo as text))
					inner join sw_empleados em on(cast(e.yaan8 as text)=trim(em.emp_an8))
					where man_tipo='".$_POST['tipo']."' and extract(month from man_fecha)=".$_POST['mes']." and extract(year from man_fecha)=".$_POST['anio']."
					and trim(em.emp_identificacion)='".$_POST['cedula']."'";
		$rs=ejecutarSql($consulta);
		$hallazgos=array();
		while($fila=pg_fetch_assoc($rs)){
			array_push($hallazgos,$fila);
		}
		return json_encode($hallazgos);
	}
	function verValorAgregado(){
		$consulta="select v.*,trim(e.yaalph) as operador,
					(case val_tipo when 'A' then 'Ascenso' when 'R' then 'Reconocimiento'
					when 'P' then 'Participación en actividades empresariales' when 'V' then 'Vacaciones'
					when 'C' then 'Cumplimiento actualizaciones y capacitaciones adicionales' end)as tipo_novedad
					from sw_oplus_valagregado v
					inner join sw_maestro_empleados e on(trim(yaoemp)=cast(v.val_codigo as text))
					inner join sw_empleados em on(cast(e.yaan8 as text)=trim(em.emp_an8))
					where val_tipo='".$_POST['tipo']."' and extract(month from val_fecha)=".$_POST['mes']." and extract(year from val_fecha)=".$_POST['anio']."
					and trim(em.emp_identificacion)='".$_POST['cedula']."'";
		$rs=ejecutarSql($consulta);
		$valor_agregado=array();
		while($fila=pg_fetch_assoc($rs)){
			array_push($valor_agregado,$fila);
		}
		return json_encode($valor_agregado);
	}
?>