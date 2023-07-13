<?php
	$pagTitle = "Reporte Acumulado - Printec";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

    $urlBreadCrumbs = "<li><a href=\"/reportes/\">Reportes</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Listado de Reportes</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">   

                   
                	

                    <?php
                    	$ssql = "
                                SELECT                              
                                SUBSTRING(a.fecha,1,4) AS anio,
                                case SUBSTRING(a.fecha,5,2)
                                when 01 then 'Enero'
                                when 02 then 'Febrero' 
                                when 03 then 'Marzo'
                                when 04 then 'Abril' 
                                when 05 then 'Mayo'
                                when 06 then 'Junio' 
                                when 07 then 'Julio'
                                when 08 then 'Agosto' 
                                when 09 then 'Septiembre'
                                when 10 then 'Octubre' 
                                when 11 then 'Noviembre'
                                when 12 then 'Diciembre' 
                                end AS mes,
                                d.descrip AS estatuscotizacion,
                                e.descrip AS estatuspagocliente,
                                CASE WHEN IFNULL(a.factura,'')=''
                                THEN 'Sin Factura'
                                ELSE a.factura
                                END AS factura,
                                COUNT(*) AS total
                                FROM ccotizacion a                           
                                INNER JOIN cestatuscotizacion d ON a.cestatuscotizacion_folio=d.folio
                                INNER JOIN cestatuspagocliente e ON a.cestatuspagocliente_folio=e.folio
                                WHERE a.sta=1 
                                AND d.sta=1
                                AND e.sta=1
                                GROUP BY SUBSTRING(a.fecha,1,4),SUBSTRING(a.fecha,5,2),d.descrip,e.descrip,a.factura
                                ";
						$resultado = $link->query($ssql);
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead>
                                <tr>                                                           	
                                    <th>Año</th>
                                    <th>Mes</th>
                                    <th>Status Cotizacion</th>
                                    <th>Status Pago Cliente</th>
                                    <th>Factura</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php 
                            	if($resultado->num_rows>0){

                                    $resultado->data_seek(0);                           
                                    while($dt=$resultado->fetch_assoc()){
                            	?>
                                <tr id="reg-<?=$dt['folio']?>">           
                                	
                                    <td><?=$dt['anio']?></td>
                                    <td><?=$dt['mes']?></td>   
                                    <td><?=$dt['estatuscotizacion']?></td>
                                    <td><?=$dt['estatuspagocliente']?></td>
                                    <td><?=$dt['factura']?></td>
                                    <td><?=$dt['total']?></td>
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