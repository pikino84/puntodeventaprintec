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

		$ssql = "update tblcotizaciondet set ";						
			$ssql .=  "sta='0', ";
			$ssql .=  "fecha_mod='$fe', ";
			$ssql .=  "hora_mod='$hr', ";
			$ssql .=  "usuario_mod='$usr' ";
			$ssql .= "where folio='$id' ";

			$link->query($ssql);	
		
		

	}

	header("Location: /cotizaciones/detalle.php?id=$id&evt=V&cw=0");
	exit();
?>