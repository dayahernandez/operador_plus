<?php 
	include '../conexion.php';
	echo $_POST['funcion']();
	function verRanking(){
		
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