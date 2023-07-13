<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();
	$id=0;

	if(isset($_POST['txtFolio'])&&!empty($_POST['txtFolio'])){
        $id = $_POST['txtFolio'];
    }



	foreach ($_REQUEST as $key => $value) {

            if(floatval($value)<=0) continue;
            
            $valores =explode("_",$key);

            if(count($valores)<=1)
                continue;

            $nom1 = $valores[0];
            $nomfolio = $valores[1];

            $ssql = "SELECT
                            p.folio AS Folio,
                            p.descrip AS Descripcion,
                            CASE WHEN p.tipo='N'
                            THEN
                            IFNULL(p.precio+(p.precio*(SELECT
                            IFNULL(mem.descuento/100,0)
                            FROM cmembresia mem 
                            WHERE mem.sta=1
                            AND mem.folio='".$_SESSION['rtMembresia']."')),0) 
                            ELSE
                            p.precio
                            END
                            AS Precio,
                            CASE WHEN p.tipo='N'
                            THEN
                            (SELECT
                                        IFNULL(mem.descuento/100,0)
                                        FROM cmembresia mem
                                        WHERE mem.sta=1
                                        AND mem.folio='".$_SESSION['rtMembresia']."')
                            ELSE
                            0
                            END
                            AS porcmembresia,
                            p.sku AS Sku,
                            p.moneda AS Moneda,
                            p.color AS Color,
                            p.existencia AS Existencia,
                            CASE WHEN IFNULL(p.Modelo,'')=''
                            THEN v.Modelo
                            ELSE 
                            p.Modelo
                            END
                            AS Modelo,
                            v.Familia AS Familia,
                            v.Subfamilia AS Subfamilia,
                            v.Material AS Meterial,
                            v.MedidaProducto AS MedidaProducto,
                            v.PaginaCatalogo AS PaginaCatalogo,
                            v.Catálogo AS Catalogo,
                            v.Descrpciondelproducto AS DescrpciondelProducto,
                            v.TipodeImpresion AS TipodeImpresion,
                            v.TécnicaImpresion1 AS TecnicaImpresion1,
                            v.PrecioDistribuidor AS PrecioDistribuidor,
                            v.PrecioPublico AS PrecioPublico,
                            v.MedidadeCajaMaster AS MedidadeCajaMaster,
                            v.Largo AS Largo,
                            v.Ancho AS Ancho,
                            v.Altura AS Altura,
                            v.Volumen AS Volumen,
                            v.Peso AS Peso,
                            v.Descripciondelartículo AS DescripciondelArticulo,
                            v.EnStock AS EnStock,
                            v.Comprometido AS Comprometido,
                            v.Disponible AS Disponible,
                            v.PORLLEGAR AS PorLlegar,
                            v.FECHAAPROXDELLEGADA AS FechaProxLlegada2,
                            v.VisibleECommerce AS VisibleECommerce,
                            p.StockInmediato,
                            p.Disponible24hrs,
                            p.Disponible48hrs,
                            p.Disponible72hrs,
                            p.Total,
                            p.Apartado,
                            p.preciolist,
                            p.PorLlegar,
                            p.FechaAproxDeLlegada,
                            v.UnidadporEmpaque AS UnidadporEmpaque,
                            CASE WHEN p.tipo='N'
                            then v.nombrecorto
                            else p.descrip
                            end as NombreCorto
                            FROM cproducto p
                            LEFT JOIN proddoblevela v ON p.sku=v.Numerodearticulo AND v.Activo='Activo'
                            WHERE p.sta=1                            
                            AND p.color!='BAJA'
                            AND p.folio='".$nomfolio."'                            
					 ";	

            
                    	
			$resultado = $link->query($ssql);

			if($resultado->num_rows>0){

                $resultado->data_seek(0);
				$producto = $resultado->fetch_assoc();   

                         

				if($producto['Existencia']>=$value && $value>0){					

					$cproducto_folio = $nomfolio;
					$cliente = 0;
					$precio = round($producto['Precio'],2);
					$cantidad = $value;	
					$concepto = $producto['NombreCorto'];	
					$cusuario_folio = $_SESSION['rtLoginFolio'];

					$sku = $producto['Sku'];
					$color =substr($producto['Color'],4);
					$modelo = $producto['Modelo'];
					$personalizacionlista = $producto['TipodeImpresion'];
					$personalizacion = "";
					$preciopersonalizacion = 0;
					$numerotintas = "";
					$precionumerotintas = 0;
					$aplicaimpresiontinta=0;
                    $porcmembresia = $producto['porcmembresia'];

					$fe = date("Ymd");
					$hr = date("H:i");
					$usr = (string)$_SESSION["rtLogin"];
                    

					if($id==0){

						$ssql="INSERT INTO ccotizacion (ccliente_folio,cusuario_folio,fecha,hora,cestatuscotizacion_folio,sta,cestatuspagocliente_folio,interna)
								VALUES ('$cliente',$cusuario_folio,'$fe','$hr','1','1','1','1')";                                

						$link->query($ssql);

						$id = $link->insert_id;
					}

					$ssql="INSERT INTO tblcotizaciondet (cusuario_folio,cproducto_folio,precio,precioexterno,cantidad,
					descuento,fecha,hora,sta,ccotizacion_folio,descrip,sku,color,modelo,personalizacionlista,
					personalizacion,preciopersonalizacion,numerotintas,precionumerotintas,aplicaimpresiontinta,porcmembresia)
					VALUES ($cusuario_folio,$cproducto_folio,$precio,$precio,$cantidad,
					0,'$fe','$hr','1','$id','$concepto','$sku','$color','$modelo','$personalizacionlista',
					'$personalizacion','$preciopersonalizacion','$numerotintas','$precionumerotintas','$aplicaimpresiontinta','$porcmembresia')";                    

					$link->query($ssql);


				}

			}              

                
    }

	header("Location: /productos/?id=$id");
	exit();
?>