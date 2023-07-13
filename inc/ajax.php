<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/backend/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/backend/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

	$lista = "<option value='0'>NA</option>";
	$ssql = "";

	if($_POST){

		$tipo = $_POST['tipo'];
		$folio = intval($_POST['folio']);

		switch($tipo){

			case "pais":
				$ssql = "SELECT folio, 
						 CASE '$ln'
						 WHEN 'ing' THEN IF(LENGTH(nom_ing)>0,nom_ing,nom_esp)
						 WHEN 'esp' THEN IFNULL(nom_esp,'') 
						 END AS nom
					     FROM cestado 
					     WHERE sta='1' 
					     AND cpais_folio='$folio'
					     ORDER BY nom_$ln ASC";
				break;
			case "estado":
				/*$ssql = "SELECT folio,
    					 CASE '$ln'
						 WHEN 'ing' THEN IF(LENGTH(nom_ing)>0,nom_ing,nom_esp)
						 WHEN 'esp' THEN IFNULL(nom_esp,'') 
						 END AS nom
    			 		 FROM cdestino 
    			 		 WHERE sta='1' 
    			 		 AND cestado_folio='$folio'	
    			 		 ORDER BY nom_$ln ASC";*/
    			$ssql = "SELECT folio, 
    					 CASE '$ln'
						 WHEN 'ing' THEN IF(LENGTH(nom_ing)>0,nom_ing,nom_esp)
						 WHEN 'esp' THEN IFNULL(nom_esp,'') 
						 END AS nom
    				     FROM cairport 
    				     WHERE sta='1'
    				     AND cestado_folio='$folio'	 
    				     ORDER BY nom_$ln ASC";

				break;
			case "estado_producto":
    			$ssql = "SELECT a.folio, 
						CASE '$ln'
						WHEN 'ing' THEN IF(LENGTH(a.nom_ing)>0,a.nom_ing,a.nom_esp)
						WHEN 'esp' THEN IFNULL(a.nom_esp,'') 
						END AS nom
						FROM cdestino a, cairport b
						WHERE a.cairport_folio=b.folio															
						AND a.sta='1'
						AND b.sta='1'
						AND b.cestado_folio='$folio'	 
    				    ORDER BY a.nom_$ln ASC";

				break;
			case "categoriaprod":
				$ssql = "SELECT folio, 
						 CASE '$ln'
						 WHEN 'ing' THEN IF(LENGTH(nom_ing)>0,nom_ing,nom_esp)
						 WHEN 'esp' THEN IFNULL(nom_esp,'') 
						 END AS nom
					     FROM csubcategoriaprod 
					     WHERE sta='1' 
					     AND ccategoriaprod_folio='$folio'
					     ORDER BY nom_$ln ASC";
				break;
			case "aeropuerto_tarifas":
				$ssql = "SELECT
							t.folio AS folio,
							t.nom_esp AS nom
							FROM tbltrasprov a, czonatraslado t
							WHERE a.cairport_folio=t.cairport_folio
							AND a.sta='1'
							AND t.sta='1'
							AND a.folio='$folio'";
				break;

		}


		$rsA = mysql_query($ssql,$link);

		if(mysql_num_rows($rsA)>0){
			while($dtA=mysql_fetch_assoc($rsA)){

				$lista .= "<option value='".$dtA['folio']."'>".$dtA['nom']."</option>";

			}
		}

	}

	echo $lista;
?>