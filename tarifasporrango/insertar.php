<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	if($_POST){

		$id = intval($_POST['txtFolio']);

		$cproducto_folio = $_POST['cproducto_folio'];	
		$crango_folio = $_POST['crango_folio'];	
		$precio = $_POST['precio'];	

		if(isset($_POST['precio_porunidad']))
			$precio_porunidad = 1;
		else
			$precio_porunidad = 0;
		

		$fe = date("Ymd");
		$hr = date("H:i");
		$usr = (string)$_SESSION["rtLogin"];
		$csucursal_folio=(int)$_SESSION['rtSucursal'];

		if($id==0){
			$ssql="insert into tblproductorango (cproducto_folio,
										crango_folio,
										precio,		
										precio_porunidad,																		
										fe_alta,
										hr_alta,
										usr_alta,sta) values ( 
										'$cproducto_folio','$crango_folio','$precio','$precio_porunidad','$fe','$hr','$usr','1'	
										)";
			$rs = mysql_query($ssql,$link);


		}else{
			
			$ssql = "update tblproductorango set ";
			$ssql .= "cproducto_folio='$cproducto_folio', ";
			$ssql .= "crango_folio='$crango_folio', ";
			$ssql .= "precio='$precio', ";
			$ssql .= "precio_porunidad='$precio_porunidad', ";
			$ssql .=  "fe_edicion='$fe', ";
			$ssql .=  "hr_edicion='$hr', ";
			$ssql .=  "usr_edicion='$usr' ";
			$ssql .= "where folio='$id' ";

			$rs = mysql_query($ssql,$link);		

		}
		
		

	}

	header("Location: /tarifasporrango/");
	exit();
?>