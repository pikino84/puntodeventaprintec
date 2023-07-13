<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/backend/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/backend/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	$lnNew = (($ln=='esp') ? 'ing' : 'esp');

	$_SESSION['rtIdioma'] = $lnNew;

	echo "1";

?>