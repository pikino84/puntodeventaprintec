<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/backend/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/backend/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	$lista = "";
	$ssql = "";

	if($_POST){

		$tipo = $_POST['tipo'];
		$folio = intval($_POST['folio']);

		switch($tipo){

			case "tours-servicios":
				$ssql = "SELECT
						desc_$ln AS descrip
						FROM cserviciotour
						WHERE sta='1'
						AND folio='$folio' ";
				break;

		}


		$rsA = mysql_query($ssql,$link);

		if(mysql_num_rows($rsA)>0){
			while($dtA=mysql_fetch_assoc($rsA)){

				$lista = $dtA['descrip'];

			}
		}

	}

	echo $lista;
?>