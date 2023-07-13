<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();
	$id=0;

	if(isset($_GET['txtFolio'])&&!empty($_GET['txtFolio'])){
        $id = $_GET['txtFolio'];
    }

	foreach ($_REQUEST as $key => $value) {
            
            $valores =explode("_",$key);

            if(count($valores)<=1)
                continue;

            $nom1 = $valores[0];
            $nomfolio = $valores[1];            

            
            switch ($nom1) {
                case 'txtPrecio':

                    $ssql ="update tblcotizaciondet set precioexterno=".$value." where folio=".$nomfolio;
                    $link->query($ssql);

                    break;                
                case 'cboPersonalizacion':                             
                            $cantidad=0;

                            //$ssql ="select cantidad from tblcotizaciondet where folio=".$nomfolio;   
                            $ssql="SELECT
                                    SUM(cantidad) AS cantidad
                                                FROM tblcotizaciondet 
                                                WHERE modelo=(SELECT modelo FROM tblcotizaciondet WHERE folio=".$nomfolio.")
                                                AND ccotizacion_folio=".$id;
                            $resultado = $link->query($ssql);

                            if($resultado->num_rows>0){

                                $resultado->data_seek(0);                           
                                while($dt=$resultado->fetch_assoc()){
                                        $cantidad=$dt['cantidad'];
                                    }
                            }               
                    
                            $ssql="SELECT
                                                                pr.precio as precio,
                                                                p.aplica_impresiontinta as aplica_impresiontinta,
                                                                ifnull(pr.precio_porunidad,0) as precio_porunidad
                                                                FROM cproducto p
                                                                INNER JOIN tblproductorango pr ON p.folio=pr.cproducto_folio
                                                                INNER JOIN crango r ON pr.crango_folio=r.folio
                                                                WHERE p.tipo='I'
                                                                AND p.sta=1
                                                                AND p.sku='".$value."'
                                                                AND ".$cantidad." BETWEEN  r.rango_inicial AND r.rango_final
                                                                AND pr.sta=1
                                                               AND r.sta=1";

                            $resultado = $link->query($ssql);

                            if($resultado->num_rows>0){

                                $resultado->data_seek(0);                           
                                while($dt=$resultado->fetch_assoc()){
                                        
                                        $ssql ="update tblcotizaciondet set personalizacion='".$value."',preciopersonalizacion=".$dt["precio"].",aplicaimpresiontinta=".$dt["aplica_impresiontinta"].",precio_porunidad=".$dt["precio_porunidad"]." where folio=".$nomfolio;
                                        $link->query($ssql);

                                        //exit();

                                    }
                            }else{

                                $ssql ="update tblcotizaciondet set personalizacion='',preciopersonalizacion=0,aplicaimpresiontinta=0,numerotintas='',precionumerotintas=0,precio_porunidad=0 where folio=".$nomfolio;
                                 $link->query($ssql);

                            }                   
                    break;
                case 'cboNumeroTintas':

                            $cantidad=1;                                         
                    
                            $ssql="SELECT
                                                                pr.precio as precio
                                                                FROM cproducto p
                                                                INNER JOIN tblproductorango pr ON p.folio=pr.cproducto_folio
                                                                INNER JOIN crango r ON pr.crango_folio=r.folio
                                                                WHERE p.tipo='T'
                                                                AND p.sta=1
                                                                AND p.sku='".$value."'
                                                                AND ".$cantidad." BETWEEN  r.rango_inicial AND r.rango_final
                                                                AND pr.sta=1
                                                                AND r.sta=1";
                           $resultado = $link->query($ssql);

                            if($resultado->num_rows>0){

                            $resultado->data_seek(0);                           
                            while($dt=$resultado->fetch_assoc()){
                                        
                                        $ssql ="update tblcotizaciondet set numerotintas='".$value."',precionumerotintas=".$dt["precio"]." where folio=".$nomfolio;
                                       $link->query($ssql);

                                    }
                            }else{

                                $ssql ="update tblcotizaciondet set numerotintas='',precionumerotintas=0 where folio=".$nomfolio;
                                $link->query($ssql);

                            }                   
                    break;
            }
         

                
    }

	header("Location: /cotizaciones/detalle.php?id=$id&evt=V&cw=");
	exit();
?>