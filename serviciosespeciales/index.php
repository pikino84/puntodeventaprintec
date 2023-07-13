<?php
	$pagTitle = "Printec - Catalogo de Servicios Especiales";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";
    $listaJS[] = "/js/plugins/ui/jquery.fancybox.js";    

    $urlBreadCrumbs = "<li><a href=\"/serviciosespeciales/\">Servicios Especiales</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Listado de Servicios Especiales</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">  

                	<ul class="toolbar">
                        <li><a href="/serviciosespeciales/captura.php?evt=I&id=0" title=""><i class="icon-shopping-cart"></i><span>Crear Servicio Especial</span></a></li>
                        <li><a href="/productos/" title=""><i class="icon-shopping-cart"></i><span>Crear Cotizacion</span></a></li>
                        <li><a href="/serviciosespeciales/exportar.php" target="blank" title=""><i class="icon-download-alt"></i><span>Exportar</span></a></li>
                    </ul> 

                    <?php
                    	$ssql = "SELECT
                                folio AS Folio,
                                descrip AS Descripcion,
                                precio AS Precio,
                                promo AS Promo,
                                moneda AS Moneda,
                                sku AS Sku,
                                existencia AS Existencia,
                                CASE tipo
                                WHEN 'N' THEN 'Normal'
                                ELSE 'Especial'
                                END AS Tipo,
                                CASE WHEN IFNULL(Imagen,'')=''
                                THEN 'http://puntodeventa.printec.mx/img/servicio-especial/servicio-especial.jpg'
                                ELSE 
                                CONCAT('http://puntodeventa.printec.mx/img/servicio-especial/',Imagen)
                                END
                                AS Imagen
                                FROM cproducto
                                WHERE sta=1
                                AND tipo='E'
                                and csucursal_folio=".$_SESSION['rtSucursal']."
                                ORDER BY descrip ASC ";
						$resultado = $link->query($ssql);
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead>
                                <tr>        
                                    <th>Acciones</th>                       	
                                    <th>Folio</th>
                                    <th>Descripción</th> 
                                    <th>Sku</th>    
                                    <th>Inventario</th>                                                             
                                    <th>Precio</th>
                                    <th>Moneda</th>
                                    <th>Imagen</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php 
                            	if($resultado->num_rows>0){

                                    $resultado->data_seek(0);                           
                                    while($dt=$resultado->fetch_assoc()){
                            	?>
                                <tr id="reg-<?=$dt['Folio']?>"> 
                                    <td>
                                        <ul class="table-controls">                                            
                                            <li><a href="#" class="btn tip accionGrid" title="Editar serviciosespeciales"><i class="fam-pencil"></i></a> </li>
                                            <li><a href="#" class="btn tip accionGrid" title="Eliminar serviciosespeciales"><i class="fam-cross"></i></a> </li>
                                        </ul>
                                    </td>                               	
                                    <td><?=$dt['Folio']?></td>
                                    <td><?=$dt['Descripcion']?></td>                                   
                                    <td><?=$dt['Sku']?></td> 
                                    <td><?=$dt['Existencia']?></td> 
                                    <td><?=number_format($dt['Precio'],2)?></td>  
                                    <td><?=$dt['Moneda']?></td>  
                                    <td><a class="lightbox" title="" href="<?=$dt['Imagen']?>"><img src="<?=$dt['Imagen']?>" width="80" height="80" /></a></td>
                                </tr>
                                <?php } }else{?>
                                <tr><td colspan="9">No existen registros</td></tr>
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