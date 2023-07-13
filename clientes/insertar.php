<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	if($_POST){

		$id = intval($_POST['txtFolio']);

		$nombre = ucfirst(strtolower($_POST['nombre']));
		$tel = $_POST['tel'];
		$email = strtolower($_POST['email']);
		$apellido = ucfirst(strtolower($_POST['apellido']));
		$cmembresia_folio = $_POST['cmembresia_folio'];
		$ccorporativo_folio = $_POST['ccorporativo_folio'];
		$ccanal_folio = $_POST['ccanal_folio'];		
		

		$fe = date("Ymd");
		$hr = date("H:i");
		$usr = (string)$_SESSION["rtLogin"];
		$csucursal_folio=(int)$_SESSION['rtSucursal'];

		if($id==0){
			$ssql="insert into ccliente (nombre,
										telefono,
										email,
										apellido,
										cmembresia_folio,
										ccorporativo_folio,
										ccanal_folio,										
										fe_alta,
										hr_alta,
										usr_alta,sta,csucursal_folio) values ( 
										'$nombre','$tel','$email','$apellido','$cmembresia_folio','$ccorporativo_folio',
										'$ccanal_folio','$fe','$hr','$usr','1',$csucursal_folio	
										)";
			$link->query($ssql);


		}else{
			
			$ssql = "update ccliente set ";
			$ssql .= "nombre='$nombre', ";
			$ssql .= "telefono='$tel', ";
			$ssql .= "email='$email', ";
			$ssql .= "apellido='$apellido', ";
			$ssql .= "cmembresia_folio='$cmembresia_folio', ";
			$ssql .= "ccorporativo_folio='$ccorporativo_folio', ";
			$ssql .= "ccanal_folio='$ccanal_folio', ";
			$ssql .=  "fe_edicion='$fe', ";
			$ssql .=  "hr_edicion='$hr', ";
			$ssql .=  "usr_edicion='$usr' ";
			$ssql .= "where folio='$id' ";

			$link->query($ssql);

		}
		
		

	}

	header("Location: /clientes/");
	exit();
?>