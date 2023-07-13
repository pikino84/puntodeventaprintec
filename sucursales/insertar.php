<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	if($_POST){

		$id = intval($_POST['txtFolio']);

		$nombre = $_POST['nombre'];
		$tel = $_POST['tel'];
		$email = strtolower($_POST['email']);
		$cmembresia_folio = $_POST['cmembresia_folio'];
		$cempresa_folio = $_POST['cempresa_folio'];
		$banco = $_POST['banco'];	
		$cuenta = $_POST['cuenta'];
		$clabe = $_POST['clabe'];	
		$facebook = $_POST['facebook'];	
		$twitter = $_POST['twitter'];
		$sitio = $_POST['sitio'];
		$num_tarjeta=$_POST['num_tarjeta'];
		$rfc=$_POST['rfc'];

		if(isset($_POST['aplica_servicioespecial']))
			$aplica_servicioespecial=1;
		else
			$aplica_servicioespecial=0;

		

		$fe = date("Ymd");
		$hr = date("H:i");
		$usr = (string)$_SESSION["rtLogin"];
		$csucursal_folio=(int)$_SESSION['rtSucursal'];
		

		if($id==0){
			$ssql="insert into csucursal (nombre,
			                            rfc,
										telefono,
										email,
										cmembresia_folio,
										cempresa_folio,
										banco,
										cuenta,
										num_tarjeta,
										clabe,	
										facebook,
										twitter,
										sitio,									
										fe_alta,
										hr_alta,
										usr_alta,sta,aplica_servicioesp) values ( 
										'$nombre','$rfc','$tel','$email','$cmembresia_folio','$cempresa_folio',
										'$banco','$cuenta','$num_tarjeta','$clabe','$facebook','$twitter','$sitio','$fe','$hr','$usr','1','aplica_servicioespecial'
										)";
			$link->query($ssql);


		}else{
			
			$ssql = "update csucursal set ";
			$ssql .= "nombre='$nombre', ";
			$ssql .= "rfc='$rfc', ";
			$ssql .= "telefono='$tel', ";
			$ssql .= "email='$email', ";
			$ssql .= "cmembresia_folio='$cmembresia_folio', ";
			$ssql .= "cempresa_folio='$cempresa_folio', ";
			$ssql .= "banco='$banco', ";
			$ssql .= "cuenta='$cuenta', ";
			$ssql .= "clabe='$clabe', ";
			$ssql .= "num_tarjeta='$num_tarjeta', ";
			$ssql .= "facebook='$facebook', ";
			$ssql .= "twitter='$twitter', ";
			$ssql .= "sitio='$sitio', ";
			$ssql .= "aplica_servicioesp='$aplica_servicioespecial', ";
			$ssql .=  "fe_edicion='$fe', ";
			$ssql .=  "hr_edicion='$hr', ";
			$ssql .=  "usr_edicion='$usr' ";
			$ssql .= "where folio='$id' ";

			$link->query($ssql);

		}
		
		

	}

	header("Location: /sucursales/");
	exit();
?>