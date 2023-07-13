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
		$csucursal_folio = $_POST['csucursal_folio'];
		$usuario = strtolower(str_replace(" ","",$_POST['usuario']));	
		$pw = $_POST['pw'];	
		

		$fe = date("Ymd");
		$hr = date("H:i");
		$usr = (string)$_SESSION["rtLogin"];
		//$csucursal_folio=(int)$_SESSION['rtSucursal'];	
		

		if($id==0){


				$ssql = "
			    				SELECT
                                u.folio AS folio
                                FROM cusuario u
                                WHERE u.usuario='$usuario'                       
										";
								$resultado = $link->query($ssql);

								if($resultado->num_rows==0){

									$ssql="insert into cusuario (nombre,
										telefono,
										email,
										csucursal_folio,
										usuario,
										pw,
										fe_alta,
										hr_alta,
										usr_alta,sta) values ( 
										'$nombre','$tel','$email','$csucursal_folio','$usuario',
										'$pw','$fe','$hr','$usr','1'	
										)";


									$link->query($ssql);
									

								}

			


		}else{

			$ssql = "
			    				SELECT
                                u.folio AS folio
                                FROM cusuario u
                                WHERE u.usuario='$usuario'   
                                and u.folio!='$id'                    
										";
								$resultado = $link->query($ssql);

								if($resultado->num_rows>0){
			
			if($pw==''){
				$ssql = "update cusuario set ";
				$ssql .= "nombre='$nombre', ";
				$ssql .= "telefono='$tel', ";
				$ssql .= "email='$email', ";
				$ssql .= "csucursal_folio='$csucursal_folio', ";
				$ssql .= "usuario='$usuario', ";
				$ssql .=  "fe_edicion='$fe', ";
				$ssql .=  "hr_edicion='$hr', ";
				$ssql .=  "usr_edicion='$usr' ";
				$ssql .= "where folio='$id' ";

			}else{
				$ssql = "update cusuario set ";
				$ssql .= "nombre='$nombre', ";
				$ssql .= "telefono='$tel', ";
				$ssql .= "email='$email', ";
				$ssql .= "csucursal_folio='$csucursal_folio', ";
				$ssql .= "usuario='$usuario', ";
				$ssql .= "pw='$pw', ";
				$ssql .=  "fe_edicion='$fe', ";
				$ssql .=  "hr_edicion='$hr', ";
				$ssql .=  "usr_edicion='$usr' ";
				$ssql .= "where folio='$id' ";
			}

			$link->query($ssql);

			}	

		}
		
		

	}

	header("Location: /vendedores/");
	exit();
?>