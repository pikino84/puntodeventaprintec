<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	if($_POST){

		$id = intval($_POST['txtFolio']);

		$nombre = $_POST['nombre'];	
		

		$fe = date("Ymd");
		$hr = date("H:i");
		$usr = (string)$_SESSION["rtLogin"];
		$csucursal_folio=(int)$_SESSION['rtSucursal'];

		if($id==0){
			$ssql="insert into ccorporativo (descrip,																				
										fe_alta,
										hr_alta,
										usr_alta,sta,csucursal_folio) values ( 
										'$nombre','$fe','$hr','$usr','1',$csucursal_folio	
										)";
			$link->query($ssql);


		}else{
			
			$ssql = "update ccorporativo set ";
			$ssql .= "descrip='$nombre', ";
			$ssql .=  "fe_edicion='$fe', ";
			$ssql .=  "hr_edicion='$hr', ";
			$ssql .=  "usr_edicion='$usr' ";
			$ssql .= "where folio='$id' ";

			$link->query($ssql);	

		}
		
		

	}

	header("Location: /corporativos/");
	exit();
?>