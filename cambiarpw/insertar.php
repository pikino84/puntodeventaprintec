<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	if($_POST){

		$id = intval($_SESSION['rtLoginFolio']);
		$pw = $_POST['pw'];	
		

		$fe = date("Ymd");
		$hr = date("H:i");
		$usr = (string)$_SESSION["rtLogin"];
		

		if($id>0){			
			
			$ssql = "update cusuario set ";				
				$ssql .= "pw='$pw', ";
				$ssql .=  "fe_edicion='$fe', ";
				$ssql .=  "hr_edicion='$hr', ";
				$ssql .=  "usr_edicion='$usr' ";
				$ssql .= "where folio='$id' ";

			$link->query($ssql);

		}
		
		

	}

	header("Location: /usuario/salir.php");
	exit();
?>