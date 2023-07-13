<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/backend/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/backend/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	$precio = 0;
	$ssql = "";

	if($_POST){

		$tipo = $_POST['tipo'];
		$folio = intval($_POST['folio']);

		switch($tipo){

			case "producto":
				$ssql = "SELECT
                                folio AS Folio,
                                descrip AS Descripcion,
                                precio AS Precio,
                                promo AS Promo,
                                moneda AS Moneda,
                                sku AS Sku,
                                CASE tipo
                                WHEN 'N' THEN 'Normal'
                                ELSE 'Especial'
                                END AS Tipo
                                FROM cproducto
                                WHERE sta=1
						and folio='$folio' ";
				break;

		}


		$rsA = mysql_query($ssql,$link);

		if(mysql_num_rows($rsA)>0){
			while($dtA=mysql_fetch_assoc($rsA)){

				
				$precio = $dtA['Precio'];
				$promo = $dtA['Promo'];

				if($precio>$promo)
					$lista = $promo;
				else
					$lista = $precio;

			}
		}

	}

	echo $lista;
?>