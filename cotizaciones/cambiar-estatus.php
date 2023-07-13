<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	if($_POST){

		$id = intval($_POST['txtFolio']);
		
		$cstatusreserva = $_POST['statusreserva'];
		$statuspagocliente = $_POST['statuspagocliente'];
		$factura = $_POST['factura'];		
		$dias=$_POST['dias'];		
		$asunto=$_POST['asunto'];
		$cbocliente=$_POST['cbocliente'];

		if(isset($_POST['envio']))
			$envio=1;
		else
			$envio=0;

		if(isset($_POST['urgencia']))
			$urgencia=1;
		else
			$urgencia=0;

		if(isset($_POST['requierelogo']))
			$requierelogo=1;
		else
			$requierelogo=0;
		
		if(isset($_POST['copiacorporativo']))
			$copiacorporativo=1;
		else
			$copiacorporativo=0;

		if(isset($_POST['interna']))
			$interna=1;
		else
			$interna=0;

		if(isset($_POST['iva']))
			$iva=1;
		else
			$iva=0;
		
		
		

		$fe = date("Ymd");
		$hr = date("H:i");
		$usr = (string)$_SESSION["rtLogin"];
		$cusuario_folio = $_SESSION['rtLoginFolio'];

		$ssql = "update ccotizacion set ";			
			$ssql .= "cestatuscotizacion_folio='$cstatusreserva', ";			
			$ssql .= "cestatuspagocliente_folio='$statuspagocliente', ";
			$ssql .= "ccliente_folio='$cbocliente', ";
			$ssql .= "interna='$interna', ";
			$ssql .=  "factura='$factura', ";
			$ssql .=  "incluye_envio='$envio', ";
			$ssql .=  "requiere_logo='$requierelogo', ";
			$ssql .=  "copiacorporativo='$copiacorporativo', ";
			$ssql .=  "asunto='$asunto', ";
			$ssql .=  "dias_entrega='$dias', ";
			$ssql .=  "incluye_iva='$iva', ";
			$ssql .=  "incluye_urgencia='$urgencia', ";
			$ssql .=  "fecha_mod='$fe', ";
			$ssql .=  "hora_mod='$hr', ";
			$ssql .=  "usuario_mod='$usr', ";
			$ssql .=  "cusuariofolio_mod='$cusuario_folio' ";
			$ssql .= "where folio='$id' ";

			$link->query($ssql);
		
		

	}

	header("Location: /cotizaciones/");
	exit();
?>