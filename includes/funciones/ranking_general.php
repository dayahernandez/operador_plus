<?php 
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
			where e.yapast='0' and u.drdl01 like '%OPERADOR%' and 1=1 
			group by e.yaan8,em.emp_fecha_ingreso order by 2";
		$rs_operadores=ejecutarSql($consulta);
		$cedulas=array();
		$codigos=array();
		$operadores=array();
		$fechas_ingreso=array();
		$ope_asoc=array();
		$fc_asoc=array();
		$ide_asoc=array();		
		$total=array();
		while($fila=pg_fetch_assoc($rs_operadores)){
			array_push($cedulas,$fila['identificacion']);
			array_push($codigos,$fila['codigo']);
			array_push($operadores,$fila['operador']);
			array_push($fechas_ingreso,$fila['emp_fecha_ingreso']);
			$ope_asoc[$fila['identificacion']]=0;
			$fc_asoc[$fila['identificacion']]=$fila['emp_fecha_ingreso'];
			$ide_asoc[$fila['identificacion']]=$fila['identificacion'];
			array_push($total,0);
		}
		$consulta="select trim(e.yassn) as identificacion,count(m.man_fecha) as hallazgos
				from sw_maestro_empleados e
				inner join sw_empleados em on(cast(e.yaan8 as text)=trim(em.emp_an8))
				inner join sw_udc u on (trim(u.drsy)='06' and trim(u.drrt)='G' and trim(e.yajbcd)=trim(u.drky))
				inner join sw_disciplinario_oficinas o on(trim(e.yamcu)=o.ofi_udc)
				left join sw_oplus_mantenimiento m on(trim(yaoemp)=cast(m.man_codigo as text) 
				and extract(month from man_fecha)=".$_POST['mes']." and extract(year from man_fecha)=".$_POST['anio']." and m.man_tipo='M')
				where e.yapast='0' and u.drdl01 like '%OPERADOR%' and 1=1  
				group by e.yaan8,em.emp_fecha_ingreso order by 2";
		$rs_mecanica=ejecutarSql($consulta);
		$mecanica=array();
		$mecanica_total=array();
		while($fila=pg_fetch_assoc($rs_mecanica)){
			$indice=array_search($fila['identificacion'],$cedulas);
			$mecanica[$indice]=$fila['hallazgos'];
			$hallazgos=$fila['hallazgos'];
			$consulta="select item_puntaje from sw_oplus_items where item_clave='mecanica'";
			$rs_puntaje_mecanica=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_mecanica)){
				if($hallazgos==0){
					$mecanica_total[$indice]=$fila_puntaje['item_puntaje'];
					$total[$indice]+=$fila_puntaje['item_puntaje'];
					$ope_asoc[$fila['identificacion']]+=$fila_puntaje['item_puntaje'];
				}else{					
					$mecanica_total[$indice]=0;
					$total[$indice]+=0;
					$ope_asoc[$fila['identificacion']]+=0;
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
				where e.yapast='0' and u.drdl01 like '%OPERADOR%' and 1=1 
				group by e.yaan8,em.emp_fecha_ingreso order by 2";
		$rs_carroceria=ejecutarSql($consulta);
		$carroceria=array();
		$carroceria_total=array();
		while($fila=pg_fetch_assoc($rs_carroceria)){
			$indice=array_search($fila['identificacion'],$cedulas);
			$carroceria[$indice]=$fila['hallazgos'];
			$hallazgos=$fila['hallazgos'];
			$consulta="select item_puntaje from sw_oplus_items where item_clave='carroceria'";
			$rs_puntaje_mecanica=ejecutarSql($consulta);
			if($fila_puntaje=pg_fetch_assoc($rs_puntaje_mecanica)){
				if($hallazgos==0){
					$carroceria_total[$indice]=$fila_puntaje['item_puntaje'];
					$total[$indice]+=$fila_puntaje['item_puntaje'];
					$ope_asoc[$fila['identificacion']]+=$fila_puntaje['item_puntaje'];
				}else{					
					$carroceria_total[$indice]=0;
					$total[$indice]+=0;
					$ope_asoc[$fila['identificacion']]+=0;
				}
			}
		}
		/* Traer el  top 3 */
		array_multisort($ope_asoc,SORT_DESC,$fc_asoc,SORT_ASC,$ide_asoc,SORT_ASC);
		$indice_primero=array_search($ide_asoc[0],$cedulas);
		$indice_segundo=array_search($ide_asoc[1],$cedulas);
		$indice_tercero=array_search($ide_asoc[2],$cedulas);
				
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
	//echo $_POST['funcion'];
?>