<?php
	$pagTitle = "Printec - Tarifas por rango";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

    $urlBreadCrumbs = "<li><a href=\"/tarifasporrango/\">Tarifas por rango</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Listado de Tarifas por rango</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">   

                    <ul class="toolbar">
                        <li><a href="/tarifasporrango/captura.php?evt=I&id=0" title=""><i class="icon-user"></i><span>Crear Tarifa</span></a></li>                        
                    </ul>

                	

                    <?php
                    	$ssql = "SELECT
                                r.folio,
                                CASE p.tipo
                                WHEN 'I' THEN 'Tipo Impresión'
                                ELSE 'Numero tinta'
                                END AS tipo,
                                p.sku AS sku,
                                p.descrip AS nombre,
                                c.rango_inicial AS inicial,
                                c.rango_final AS final,
                                r.precio AS precio,
                                ifnull(r.precio_porunidad,0) as precio_porunidad
                                FROM tblproductorango r
                                INNER JOIN cproducto p ON r.cproducto_folio=p.folio
                                INNER JOIN crango c ON r.crango_folio=c.folio
                                WHERE r.sta=1
                                AND p.sta=1
                                AND c.sta=1
                                ORDER BY p.tipo,p.folio,c.rango_inicial ASC ";
						$rs = mysql_query($ssql,$link);
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead>
                                <tr>       
                                    <th>Acciones</th>                         	
                                    <th>Folio</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>Inicial</th>  
                                    <th>Final</th> 
                                    <th>Precio</th>
                                    <th>Precio x unidad?</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php 
                            	if(mysql_num_rows($rs)>0){
                            		while($dt=mysql_fetch_assoc($rs)){
                            	?>
                                <tr id="reg-<?=$dt['folio']?>">
                                    <td>
                                        <ul class="table-controls">
                                            <li><a href="#" class="btn tip accionGrid" title="Editar tarifasporrango"><i class="fam-pencil"></i></a> </li>
                                            <li><a href="#" class="btn tip accionGrid" title="Eliminar tarifasporrango"><i class="fam-cross"></i></a> </li>
                                        </ul>
                                    </td>
                                	
                                    <td><?=$dt['folio']?></td>
                                    <td><?=$dt['tipo']?></td>
                                    <td><?=$dt['nombre']?></td>
                                    <td><?=$dt['inicial']?></td>   
                                    <td><?=$dt['final']?></td>
                                    <td><?=$dt['precio']?></td>
                                    <td>
                                    	<?php if($dt['precio_porunidad']==1){?>
                                    		SI
                                    	<?php }else{?>
                                    		NO
                                    	<?php }?>

                                    </td>
                                </tr>
                                <?php } }else{?>
                                <tr><td colspan="8">No existen registros</td></tr>
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