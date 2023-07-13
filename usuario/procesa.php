<?php
	error_reporting(0);
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);

	if ($_POST) {

		if(isset($_POST['txtUsu'])&&!empty($_POST['txtUsu'])&&isset($_POST['txtPw'])&&!empty($_POST['txtPw'])){

			$txtUsu = limpiarCampo($_POST['txtUsu'],$link);
			$txtPw = limpiarCampo($_POST['txtPw'],$link);
			$txtIdioma = limpiarCampo($_POST['txtIdioma'],$link);

			$ssql = "SELECT 
					u.folio as folio,
			        u.usuario AS usr,
					u.csucursal_folio AS csucursal_folio,
					e.archivo AS logo,
					ifnull(s.superadmin,0) as superadmin,
					ifnull(u.admin,0) as admin,
					ifnull(s.cmembresia_folio,0) as  cmembresia_folio,
					ifnull(s.aplica_servicioesp,0) as  aplica_servicioesp
					FROM cusuario u
					INNER JOIN csucursal s ON u.csucursal_folio=s.folio 
					INNER JOIN cempresa e ON s.cempresa_folio=e.folio
					WHERE u.usuario='".$txtUsu."' and u.pw='".$txtPw."' 
					and u.sta='1'
					and s.sta='1'
					AND e.sta='1'
					 ";
			
			$resultado = $link->query($ssql);

			if($resultado->num_rows>0){

				$resultado->data_seek(0);
				$dt = $resultado->fetch_assoc();

				session_start();
				$_SESSION['rtLoginFolio'] = $dt['folio'];
				$_SESSION['rtLogin'] = $dt['usr'];
				$_SESSION['rtSucursal'] = $dt['csucursal_folio'];
				$_SESSION['rtMembresia'] = $dt['cmembresia_folio'];
				$_SESSION['rtSuperAdmin'] = $dt['superadmin'];
				$_SESSION['rtAdmin'] = $dt['admin'];
				$_SESSION['rtAplicaSE'] = $dt['aplica_servicioesp'];				
				$_SESSION['rtLogo'] = $dt['logo'];
				$_SESSION['rtIdioma'] = $txtIdioma;
				$_SESSION['ultimoacceso'] = mktime();

			}


		}

	}

	header('Location: /');
	exit();
?>

