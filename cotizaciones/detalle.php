<?php
    $id=0;
    $cw=0;

    if($_GET){
        if($_GET['evt']=='V'&&isset($_GET['id'])&&isset($_GET['cw'])){
            $id=intval($_GET['id']);
            $cw=intval($_GET['cw']);
        }
    }

	$pagTitle = "Detalle de la cotización - Printec";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

    

    $urlBreadCrumbs = "<li><a href=\"/cotizaciones/\">Cotizaciones</a></li>";

    if($cw==0)
        $urlBreadCrumbs .= "<li><a href=\"/productos/?id=$id\">Agregar producto a cotizacion $id</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Detalle de la cotizacion <a href="/cotizaciones/edit_cotizacion.php?id=<?=$id?>&evt=U">PRINT<?=$id?></a></h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">  

                    <ul class="toolbar">
                        <li><a href="/cotizaciones/solicitar-cotizacion.php?evt=V&id=<?=$id?>" title=""><i class="icon-credit-card"></i><span>Verificar almacén</span></a></li>
                        <li><a href="/cotizaciones/visualizar-cotizacion.php?evt=V&id=<?=$id?>" target="_blank" title=""><i class="icon-search"></i><span>Visualizar cotización</span></a></li>
                        <li><a href="/cotizaciones/generar-cotizacion.php?evt=V&id=<?=$id?>" title=""><i class="icon-envelope-alt"></i><span>Generar y enviar cotización por correo</span></a></li>
                    </ul>            	

                    <?php
                    	$ssql = " 
                                SELECT
                                a.folio,
                                ifnull(a.descrip,p.descrip) as Descripcion,
                                a.precio,
                                a.descuento,
                                a.cantidad,
                                (a.precio - a.descuento)*a.cantidad as total,
                                b.cerrado,
                                a.sku as clave,
                                a.color as color,
                                a.personalizacionlista as personalizacionlista,
                                a.personalizacion as personalizacion,
                                a.precioexterno as precioexterno,
                                a.preciopersonalizacion as preciopersonalizacion,
                                a.numerotintas as numerotintas,
                                a.precionumerotintas as precionumerotintas,
                                a.aplicaimpresiontinta as aplicaimpresiontinta,
                                ifnull(b.interna,0) as interna,
                                ifnull(b.incluye_iva,0) as incluye_iva,
                                ifnull(b.incluye_urgencia,0) as incluye_urgencia,
                                a.modelo as modelo,
                                p.tipo as tipo,
                                ifnull(a.precio_porunidad,0) as precio_porunidad,
                                CASE WHEN IFNULL(p.Imagen,'')=''
                                THEN 'https://puntodeventa.printec.mx/img/servicio-especial/servicio-especial.jpg'
                                ELSE 
                                CONCAT('https://puntodeventa.printec.mx/img/servicio-especial/',p.Imagen)
                                END
                                AS Imagen
                                FROM tblcotizaciondet a
                                INNER JOIN ccotizacion b ON a.ccotizacion_folio=b.folio                                
                                INNER JOIN cproducto p ON a.cproducto_folio=p.folio
                                WHERE a.sta=1
                                AND a.ccotizacion_folio=$id                                
                                ORDER BY a.folio DESC 
                                                ";
						$resultado = $link->query($ssql);                      
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>    
                                    <th>Accion</th>  
                                    <th>Clave</th>                            
                                    <th>Concepto</th>
                                    <th>Color</th>
                                    <th>Personalización</th>
                                    <th>Cantidad</th>                                                                        
                                    <th>Precio Unitario</th>                             
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form method="get" action="/cotizaciones/recalcular.php">
                                    <input type="hidden" name="txtFolio" id="txtFolio" value="<?=$id?>">
                            	<?php 
                            	if($resultado->num_rows>0){

                                    $subTotal=0;
                                    $incluye_iva=1;
                                    $incluye_urgencia=0;

                            		$resultado->data_seek(0);                             
                                    while($dt=$resultado->fetch_assoc()){

                                        $incluye_iva=$dt['incluye_iva'];
                                        $incluye_urgencia=$dt['incluye_urgencia'];

                                        //$total+=$dt['total'];
                                        $precioproductoli = 0;

                                                if($dt['interna']==1&&$dt['precioexterno']>0)
                                                    $precioproductoli = $dt['precioexterno'];
                                                 else
                                                    $precioproductoli = $dt['precio'];

                                            if($dt['precio_porunidad']==1)
                                                $costo = ($dt['cantidad']*$precioproductoli)+($dt['cantidad']*$dt['preciopersonalizacion']*$dt['precionumerotintas']);
                                            else
                                                $costo = ($dt['cantidad']*$precioproductoli)+($dt['preciopersonalizacion']*$dt['precionumerotintas']);


                                            $subTotal+=$costo;

                            	?>
                                <tr id="reg-<?=$dt['folio']?>">
                                    <?php if($dt['cerrado']==0){?>
                                    <td><a href="eliminar_det.php?id=<?=$dt['folio']?>&evt=U" class="accionDel"><i class="fam-cross"></i></a></td> 
                                    <?php }else{?>
                                    <td></td>
                                    <?php }?>
                                    <td align="center">
                                        <?php if($dt['tipo']=='N'){
                                            $fotoarticulo = "https://www.doblevela.com/images/".str_replace(" ","",$dt['modelo']).".png";

                                            if (!file_exists($fotoarticulo))
                                                  $fotoarticulo = str_replace(".png",".jpg",$fotoarticulo);
                                            
                                            if (!file_exists($fotoarticulo))
                                                  $fotoarticulo = "https://www.doblevela.com/images/large/".str_replace(" ","",$dt['modelo'])."_lrg.jpg";
                                            
                                        ?>
                                        <img src="<?=$fotoarticulo?>" width="100" />
                                        <?php }else{ ?>
                                            <img src="<?=$dt['Imagen']?>" width="100" />
                                        <?php }?>
                                        <br/>
                                        <?=$dt['clave']?>                                            
                                    </td>
                                    <td><?=$dt['Descripcion']?></td>
                                    <td><?=$dt['color']?></td>
                                    <td>
                                                <?php $listap = explode(" ",$dt['personalizacionlista']) ?>
                                                <select name="cboPersonalizacion_<?=$dt['folio']?>" id="cboPersonalizacion_<?=$dt['folio']?>" style="width:120px">
                                                    <option value="select">Tipo</option>
                                                    <?php foreach ($listap as $lip) { if($lip=='') continue;
                                                        ?>
                                                        <option <?php if($lip==$dt['personalizacion']){?> selected="selected" <?php }?> value="<?=$lip?>"><?=$lip?></option>
                                                    <?php }?>
                                                </select>

                                                <?php if($dt['aplicaimpresiontinta']==1){?>

                                                    <select name="cboNumeroTintas_<?=$dt['folio']?>" id="cboNumeroTintas_<?=$dt['folio']?>" style="width:120px">
                                                        <option value="select">Tintas</option>
                                                        <?php 
                                                        $ssql="SELECT
                                                                sku AS Sku,
                                                                descrip AS Descrip
                                                                FROM cproducto
                                                                WHERE sta=1
                                                                AND tipo='T'";
                                                        $rsTintas = $link->query($ssql);
                        
                                                        if($rsTintas->num_rows>0){

                                                            $rsTintas->data_seek(0);                           
                                                            while($dtTintas=$rsTintas->fetch_assoc()){

                                                        ?>
                                                            <option <?php if($dtTintas['Sku']==$dt['numerotintas']){?> selected="selected" <?php }?> value="<?=$dtTintas['Sku']?>"><?=$dtTintas['Descrip']?></option>
                                                    <?php } } ?>

                                                    </select>


                                                <?php }?>

                                            </td>
                                    <td><?=$dt['cantidad']?></td>                                    
                                    <td><?=$precioproductoli?></td>                                   
                                    <td><?=number_format($costo,2)?></td>    
                                </tr>
                                <?php } 

                                if($incluye_urgencia==1){
                                    $subTotal=$subTotal+($subTotal*.3);
                                }

                                ?>

                                <?php if($incluye_iva==1){ ?>
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2"><input class="btn btn-blue" type="submit" name="btnRecalcular" value="Recalcular Precios"></td>
                                    <td align="right"><strong>Subtotal</strong></td>
                                    <td><strong><?=number_format($subTotal,2)?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td align="right"><strong>Iva</strong></td>
                                    <td><strong><?=number_format(($subTotal*1.16)-$subTotal,2)?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td align="right"><strong>Retención ISR</strong></td>
                                    <td><strong><?=number_format(($subTotal*(1.25/100)),2)?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="6"></td>
                                    <td align="right"><strong>Total a pagar</strong></td>
                                    <td><strong><?=number_format(($subTotal*1.16)-($subTotal*(1.25/100)),2)?></strong></td>
                                </tr>
                                <?php }else{?>
                                <tr>
                                    <td colspan="6"></td>
                                    <td align="right"><strong>Total a pagar</strong></td>
                                    <td><strong><?=number_format($subTotal,2)?></strong></td>
                                </tr>
                                <?php }?>


                                <?php }else{?>
                                <tr><td colspan="6">No existen registros</td></tr>
                                <?php }?>
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /default datatable -->

		    </div>
		    <!-- /content wrapper -->

		</div>
		<!-- /content -->

	</div>
	<!-- /content container -->


	<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/footer.php');
?>