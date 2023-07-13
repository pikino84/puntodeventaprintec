<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	if($_POST){

		$id = intval($_POST['txtFolio']);

		$descrip = $_POST['descrip'];			
		$email = $_POST['email'];		

		if($url=='')
			$url='#';

		$uploadedfileload=true;
		$siSubio = false;
		$uploadedfile_size=$_FILES['imagen']['size'];
		
		if ($_FILES['imagen']['size']>200000){
			$msg=$msg."El archivo es mayor que 200KB, debes reduzcirlo antes de subirlo<BR>";
			$uploadedfileload=false;
		}

		if (!($_FILES['imagen']['type'] =="image/jpeg" OR $_FILES['imagen']['type'] =="image/jpg" OR $_FILES['imagen']['type'] =="image/gif")){
			$msg=$msg." Tu archivo tiene que ser JPG o GIF. Otros archivos no son permitidos<BR>";
			$uploadedfileload=false;
		}

		$file_name=$_FILES['imagen']['name'];
		
		$add="../img/$file_name";

		if($uploadedfileload){

			if(move_uploaded_file($_FILES['imagen']['tmp_name'], $add)){
				//echo " Ha sido subido satisfactoriamente";
				$siSubio = true;
			}else{
				//echo "Error al subir el archivo";
			}

		}else{
			//echo $msg;
		}

		$fe = date("Ymd");
		$hr = date("H:i");
		$usr = (string)$_SESSION["rtLogin"];
	
		if($siSubio){
			

			if($id==0){
				$ssql="insert into cempresa (email,
											archivo,
											logo_cotizacion,
											nombre,
											fe_alta,
											hr_alta,
											usr_alta,sta) values ( 
											'$email','$file_name','$file_name','$descrip','$fe','$hr','$usr','1'	
											)";
			}else{
				
				$ssql = "update cempresa set ";
				$ssql .= "email='$email', ";
				$ssql .= "archivo='$file_name', ";
				$ssql .= "logo_cotizacion='$file_name', ";
				$ssql .= "nombre='$descrip', ";				
				$ssql .=  "fe_edicion='$fe', ";
				$ssql .=  "hr_edicion='$hr', ";
				$ssql .=  "usr_edicion='$usr' ";
				$ssql .= "where folio='$id' ";
			}
			
			$link->query($ssql);
		}else{

			if($id==0){
				$ssql="insert into cempresa (email,
											nombre,
											fe_alta,
											hr_alta,
											usr_alta,sta) values ( 
											'$email','$descrip','$fe','$hr','$usr','1'	
											)";
			}else{
				
				$ssql = "update cempresa set ";
				$ssql .= "email='$email', ";
				$ssql .= "nombre='$descrip', ";				
				$ssql .=  "fe_edicion='$fe', ";
				$ssql .=  "hr_edicion='$hr', ";
				$ssql .=  "usr_edicion='$usr' ";
				$ssql .= "where folio='$id' ";
			}
			
			$link->query($ssql);



		}

	}

	header("Location: /empresas/");
	exit();
?>