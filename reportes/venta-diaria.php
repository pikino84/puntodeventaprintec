<?php
	$pagTitle = "Reporte de Venta Diaría - Printec";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

    $urlBreadCrumbs = "<li><a href=\"/reportes/\">Reportes</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Reporte de Venta diaría</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">   

                   
                	

                    <?php
                    	$ssql = "
                        SELECT
                        a.folio AS folio,    
                        CONCAT(SUBSTRING(a.fecha,7,2),'/',SUBSTRING(a.fecha,5,2),'/',SUBSTRING(a.fecha,1,4)) as fecha,
                        a.hora AS hora,
                        CONCAT('[',c.folio,'] ',c.nombre,' ',c.apellido) AS cliente,
                        u.usuario AS vendedor,    
                        d.descrip AS estatuscotizacion,
                        e.descrip AS estatuspagocliente,
                        CASE WHEN IFNULL(a.factura,'')=''
                        THEN 'Sin Factura'
                        ELSE a.factura
                        END AS factura,
                        a.dias_entrega AS dias_entrega,
                        IFNULL(a.incluye_envio,0) AS incluye_envio,
                        IFNULL(a.incluye_iva,0) AS incluye_iva,
                        IFNULL(a.incluye_urgencia,0) AS incluye_urgencia,
                        SUM(totales.subtotal) AS subtotal                  
                        FROM ccotizacion a
                        LEFT JOIN ccliente c ON a.ccliente_folio=c.folio AND c.sta=1
                        INNER JOIN cusuario u ON a.cusuario_folio=u.folio                     
                        INNER JOIN cestatuscotizacion d ON a.cestatuscotizacion_folio=d.folio
                        INNER JOIN cestatuspagocliente e ON a.cestatuspagocliente_folio=e.folio
                        INNER JOIN 
                        (
                        SELECT 
                        cd.ccotizacion_folio AS ccotizacion_folio, 
                        CASE WHEN IFNULL(cd.precio_porunidad,0)=1
                        THEN (cd.cantidad*(CASE WHEN a.interna=1 AND cd.precioexterno>0 THEN cd.precioexterno ELSE cd.precio END))+(cd.cantidad*cd.preciopersonalizacion*cd.precionumerotintas)
                        ELSE (cd.cantidad*(CASE WHEN a.interna=1 AND cd.precioexterno>0 THEN cd.precioexterno ELSE cd.precio END))+(cd.preciopersonalizacion*cd.precionumerotintas)
                        END AS subtotal
                        FROM ccotizacion a
                        INNER JOIN tblcotizaciondet cd ON a.folio=cd.ccotizacion_folio
                        WHERE cd.sta=1
                        ) AS totales ON a.folio=totales.ccotizacion_folio
                        WHERE a.sta=1
                        AND u.sta=1
                        AND d.sta=1
                        AND e.sta=1
                        GROUP BY a.folio,a.fecha,a.hora,c.folio,c.nombre,c.apellido,u.usuario,d.descrip,e.descrip,a.factura,a.dias_entrega,a.incluye_envio,a.incluye_iva,a.incluye_urgencia
                        ORDER BY a.folio DESC
                                ";
						$resultado = $link->query($ssql);
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead>
                                <tr>                                                           	
                                    <th>Folio</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th>Subtotal</th>
                                    <th>IVA</th>
                                    <th>Retención ISR</th>
                                    <th>Total</th>
                                    <th>Status Cotizacion</th>
                                    <th>Status Pago Cliente</th>
                                    <th>Factura</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php 
                            	if($resultado->num_rows>0){

                                    $resultado->data_seek(0);                           
                                    while($dt=$resultado->fetch_assoc()){

                                        $subTotal = $dt['subtotal'];
                                        //$costoUrgencia=0;

                                        if($dt['incluye_urgencia']==1){
                                            $subTotal=$subTotal+($subTotal*.3);
                                        }
                                        
                                        if($dt['incluye_urgencia']==1){
                                            $iva = number_format(($subTotal*1.16)-$subTotal,2);
                                            $retencion = number_format(($subTotal*(1.25/100)),2);
                                            $total = number_format(($subTotal*1.16)-($subTotal*(1.25/100)),2);
                                        }else{
                                            $iva = 0;
                                            $retencion = 0;
                                            $total = number_format(($subTotal*1.16)-($subTotal*(1.25/100)),2);
                                        }

                                        
                            	?>
                                <tr id="reg-<?=$dt['folio']?>">                                               
                                    <td><a href="/cotizaciones/edit_cotizacion.php?id=<?=$dt['folio']?>&evt=U" target="_blank" >PRINT<?=$dt['folio']?></a></td>
                                    <td><?=$dt['fecha']?> <?=$dt['hora']?></td>  
                                    <td><?=$dt['cliente']?></td> 
                                    <td><?=$dt['vendedor']?></td> 
                                    <td><?=$subTotal?></td> 
                                    <td><?=$iva?></td> 
                                    <td><?=$retencion?></td> 
                                    <td><?=$total?></td> 
                                    <td><?=$dt['estatuscotizacion']?></td>
                                    <td><?=$dt['estatuspagocliente']?></td>
                                    <td><?=$dt['factura']?></td>
                                </tr>
                                <?php } }else{?>
                                <tr><td colspan="6" align="center">No existen registros</td></tr>
                                <?php }?>
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