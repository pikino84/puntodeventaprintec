<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	if($_GET){

		if(isset($_GET['evt'])&&$_GET['evt']=="D"){

			if(isset($_GET['id'])&&!empty($_GET['id'])){

				$folio = intval($_GET['id']);

				$fe = date("Ymd");
				$hr = date("H:i");
				$usr = (string)$_SESSION["rtLogin"];

				$ssql  = "update cproducto set sta='0',fe_edicion='$fe',hr_edicion='$hr',usr_edicion='$usr' where folio='$folio'  ";		
				

				$link->query($ssql);
				

			}

		}
	}

	header("Location: /serviciosespeciales/");
	exit();
?>