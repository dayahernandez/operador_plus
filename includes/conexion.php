<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
	function conectar(){
		$conn=@pg_connect("dbname=jjj host=localhost user=postgres");
		return $conn;	
	}
	function ejecutarSql($sql) {
		if ($sql!='') {
			$conn=conectar();
			if (!$conn) {
				echo "No se pudo conectar a la base de datos";
			} else {
				$rs=pg_query ($conn, $sql);
				if (!$rs) echo $sql;
				return $rs;
			}
		}
	}
?>