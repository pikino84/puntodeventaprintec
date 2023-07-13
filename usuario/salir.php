<?php
	
	session_start();
	$_SESSION["rtLogin"] = null;
	$_SESSION["ultimoacceso"] = null;

	$_SESSION['rtLoginFolio'] = null;
	$_SESSION['rtSucursal'] = null;
	$_SESSION['rtMembresia'] = null;
	$_SESSION['rtSuperAdmin'] = null;
	$_SESSION['rtLogo'] = null;
	$_SESSION['rtIdioma'] = null;

	session_destroy();

	header("Location: /");
	exit();
?>

