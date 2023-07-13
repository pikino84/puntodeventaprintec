<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	if($_GET){

		$id = intval($_GET['id']);	
		

		$fe = date("Ymd");
		$hr = date("H:i");
		$usr = (string)$_SESSION["rtLogin"];
		$csucursal_folio=(int)$_SESSION['rtSucursal'];

		$ssql = "update ccotizacion set ";						
			$ssql .=  "cerrado='0', ";
			$ssql .=  "fecha_abierto='$fe', ";
			$ssql .=  "hora_abierto='$hr', ";
			$ssql .=  "cusuariofolio_abierto='$csucursal_folio' ";
			$ssql .= "where folio='$id' ";

			$link->query($ssql);
		
		

	}

	header("Location: /cotizaciones/edit_cotizacion.php?id=$id&evt=U");
	exit();
?>