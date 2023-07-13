<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	if($_POST){

		$id = intval($_POST['txtFolio']);

		$nombre = ucfirst(strtolower($_POST['nombre']));
		$precio = $_POST['precio'];
		$sku = $_POST['sku'];
		$inventario = $_POST['inventario'];

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
		
		$add="../img/servicio-especial/$file_name";

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
		$csucursal_folio=(int)$_SESSION['rtSucursal'];


		if($siSubio){

			if($id==0){
				$ssql="insert into cproducto (descrip,
											precio,
											promo,
											moneda,
											sku,
											color,
											existencia,
											total,
											modelo,
											tipo,
											fe_alta,
											hr_alta,
											Imagen,
											usr_alta,sta,csucursal_folio) values ( 
											'$nombre','$precio','$precio','MXN','$sku','',$inventario,$inventario,'$sku','E'
											,'$fe','$hr','$file_name','$usr','1',$csucursal_folio
											)";
				$link->query($ssql);


			}else{
				
				$ssql = "update cproducto set ";
				$ssql .= "descrip='$nombre', ";
				$ssql .= "precio='$precio', ";
				$ssql .= "promo='$precio', ";
				$ssql .= "moneda='MXN', ";
				$ssql .= "sku='$sku', ";
				$ssql .= "modelo='$sku', ";
				$ssql .= "tipo='E', ";
				$ssql .= "existencia='$inventario', ";
				$ssql .= "total='$inventario', ";
				$ssql .= "Imagen='$file_name', ";
				$ssql .=  "fe_edicion='$fe', ";
				$ssql .=  "hr_edicion='$hr', ";
				$ssql .=  "usr_edicion='$usr' ";
				$ssql .= "where folio='$id' ";

				$link->query($ssql);	

			}

		}else
		{


			if($id==0){
				$ssql="insert into cproducto (descrip,
											precio,
											promo,
											moneda,
											sku,
											color,
											existencia,
											total,
											modelo,
											tipo,
											fe_alta,
											hr_alta,
											usr_alta,sta,csucursal_folio) values ( 
											'$nombre','$precio','$precio','MXN','$sku','',$inventario,$inventario,'$sku','E'
											,'$fe','$hr','$usr','1',$csucursal_folio
											)";
				$link->query($ssql);


			}else{
				
				$ssql = "update cproducto set ";
				$ssql .= "descrip='$nombre', ";
				$ssql .= "precio='$precio', ";
				$ssql .= "promo='$precio', ";
				$ssql .= "moneda='MXN', ";
				$ssql .= "sku='$sku', ";
				$ssql .= "modelo='$sku', ";
				$ssql .= "tipo='E', ";
				$ssql .= "existencia='$inventario', ";
				$ssql .= "total='$inventario', ";
				$ssql .=  "fe_edicion='$fe', ";
				$ssql .=  "hr_edicion='$hr', ";
				$ssql .=  "usr_edicion='$usr' ";
				$ssql .= "where folio='$id' ";

				$link->query($ssql);	

			}

		}
		
		

	}

	header("Location: /serviciosespeciales/");
	exit();
?>