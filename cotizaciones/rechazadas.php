<?php
	$pagTitle = "Listado de Cotizaciones - Printec";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js?v=1";

    $urlBreadCrumbs = "<li><a href=\"/cotizaciones/\">Cotizaciones</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Listado de Cotizaciones Rechazadas</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">    

                    <ul class="toolbar">
                        <li><a href="/cotizaciones/captura.php?evt=I&id=0" title=""><i class="icon-shopping-cart"></i><span>Crear Cotizacion</span></a></li>
                        <li><a href="/clientes/captura.php?evt=I&id=0" title=""><i class="icon-user"></i><span>Crear Cliente</span></a></li>
                    </ul>              	

                    <?php
                    	$ssql = " 
                                SELECT
                                a.folio,
                                CONCAT('[',b.folio,'] ',b.nombre,' ',b.apellido) AS cliente,                                
                                CONCAT(SUBSTRING(a.fecha,7,2),'/',SUBSTRING(a.fecha,5,2),'/',SUBSTRING(a.fecha,1,4)) as fecha,
                                a.hora,
                                c.nombre as vendedor,
                                s.nombre as sucursal, 
                                d.descrip AS estatuscotizacion,
                                e.descrip AS estatuspagocliente,
                                d.clase AS claseestatuscotizacion,
                                e.clase AS claseestatuspagocliente,
                                a.factura,
                                a.cerrado
                                FROM ccotizacion a
                                LEFT JOIN ccliente b ON a.ccliente_folio=b.folio AND b.sta=1
                                INNER JOIN cusuario c ON a.cusuario_folio=c.folio
                                INNER JOIN csucursal s ON c.csucursal_folio=s.folio                                
                                INNER JOIN cestatuscotizacion d ON a.cestatuscotizacion_folio=d.folio
                                INNER JOIN cestatuspagocliente e ON a.cestatuspagocliente_folio=e.folio
                                WHERE a.sta=1                                
                                AND c.sta=1
                                AND s.sta=1
                                AND d.sta=1
                                AND e.sta=1
                                AND d.folio in (2,4)
                                AND s.folio=(case when ".$_SESSION['rtSuperAdmin']."=1 then s.folio else ".$_SESSION['rtSucursal']." end)
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
                                    <th>EstatusCotizacion</th>
                                    <th>EstatusPagoCliente</th>
                                    <th>Factura</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php 
                            	if($resultado->num_rows>0){

$resultado->data_seek(0);                           
while($dt=$resultado->fetch_assoc()){
                            	?>
                                <tr id="reg-<?=$dt['folio']?>"> 
                                    <td><a href="edit_cotizacion.php?id=<?=$dt['folio']?>&evt=U">PRINT<?=$dt['folio']?></a></td>
                                    <td><?=$dt['fecha']?> <?=$dt['hora']?></td>
                                    <td><?=$dt['cliente']?></td>
                                    <td><?=$dt['vendedor']?> - <?=$dt['sucursal']?></td>
                                    <td><span class="label  <?php echo $dt['claseestatuscotizacion']; ?>"><?=$dt['estatuscotizacion']?></span></td>
                                    <td><span class="label  <?php echo $dt['claseestatuspagocliente']; ?>"><?=$dt['estatuspagocliente']?></span></td>
                                    <td><span <?php if($dt['factura']!=''){?> class="label label-success" <?php }?>><?=$dt['factura']?></span></td>   
                                </tr>
                                <?php } }else{?>
                                <tr><td colspan="7">No existen registros</td></tr>
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