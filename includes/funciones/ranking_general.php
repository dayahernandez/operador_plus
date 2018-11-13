<?php
	if(!isset($_POST['funcion'])){
		header("Location: ../../index.php");
		die();
	}
	include '../conexion.php';
	echo $_POST['funcion']();
	function verRanking(){
		$consulta="select * from sw_oplus_items";
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
		$consulta="select trim(e.yassn) as identificacion,count(m.man_fecha) as hallazgos
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
		while($fila=pg_fetch_assoc($rs_mecanica)){
			$indice=array_search($fila['identificacion'],$cedulas);
			$hallazgos=$fila['hallazgos'];
			$consulta="select item_puntaje from sw_oplus_items where item_clave='mecanica'";
			$rs_puntaje_mecanica=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_mecanica)){
				if($hallazgos==0){
					$mecanica_total[$fila['identificacion']]=$fila_puntaje['item_puntaje'];
					$total[$indice]+=$fila_puntaje['item_puntaje'];
				}else{					
					$mecanica_total[$fila['identificacion']]=0;
					$total[$indice]+=0;
				}
			}
		}
		$consulta="select e.yaan8,count(m.man_fecha) as hallazgos,em.emp_fecha_ingreso,trim(e.yaalph) as operador,
				trim(e.yaoemp) as codigo,trim(e.yassn) as identificacion,e.yamcu
				from sw_maestro_empleados e
				inner join sw_empleados em on(cast(e.yaan8 as text)=trim(em.emp_an8))
				inner join sw_udc u on (trim(u.drsy)='06' and trim(u.drrt)='G' and trim(e.yajbcd)=trim(u.drky))
				inner join sw_disciplinario_oficinas o on(trim(e.yamcu)=o.ofi_udc)
				left join sw_oplus_mantenimiento m on(trim(yaoemp)=cast(m.man_codigo as text) 
				and extract(month from man_fecha)=".$_POST['mes']." and extract(year from man_fecha)=".$_POST['anio']." and m.man_tipo='C')
				where e.yapast='0' and u.drdl01 like '%OPERADOR%'  and trim(e.yaoemp)<>'' and 1=1 
				group by e.yaan8,em.emp_fecha_ingreso order by 2";
		$rs_carroceria=ejecutarSql($consulta);
		$carroceria_total=array();
		while($fila=pg_fetch_assoc($rs_carroceria)){
			$indice=array_search($fila['identificacion'],$cedulas);
			$hallazgos=$fila['hallazgos'];
			$consulta="select item_puntaje from sw_oplus_items where item_clave='carroceria'";
			$rs_puntaje_mecanica=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_mecanica)){
				if($hallazgos==0){
					$carroceria_total[$fila['identificacion']]=$fila_puntaje['item_puntaje'];
					$total[$indice]+=$fila_puntaje['item_puntaje'];
				}else{					
					$carroceria_total[$fila['identificacion']]=0;
					$total[$indice]+=0;
				}
			}
		}
		
		array_multisort($total,SORT_DESC,$fechas_ingreso,SORT_ASC,$operadores,SORT_ASC,$codigos,$cedulas);
		/* Traer el  top 3 */
		//array_multisort($ope_asoc,SORT_DESC,$fc_asoc,SORT_ASC,$ide_asoc,SORT_ASC);
		/*$indice_primero=array_search($ide_asoc[0],$cedulas);
		$indice_segundo=array_search($ide_asoc[1],$cedulas);
		$indice_tercero=array_search($ide_asoc[2],$cedulas);*/
				
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
?>