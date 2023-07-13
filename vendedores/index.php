<?php
	$pagTitle = "Catalogo de Vendedores - Printec";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

    $urlBreadCrumbs = "<li><a href=\"/vendedores/\">Vendedores</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Listado de Vendedores</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">   

                	<ul class="toolbar">
                        <li><a href="/vendedores/captura.php?evt=I&id=0" title=""><i class="icon-user"></i><span>Crear Vendedor</span></a></li>
                        <li><a href="/productos/" title=""><i class="icon-user"></i><span>Crear Cotización</span></a></li>
                    </ul>

                    <?php
                    	$ssql = "SELECT
                                u.folio AS folio,
                                u.nombre AS nombre,
                                u.usuario AS usuario,
                                u.pw AS pw,
                                u.csucursal_folio AS csucursal_folio,
                                u.email AS email,
                                u.telefono AS telefono,
                                s.nombre AS sucursal
                                FROM cusuario u
                                INNER JOIN csucursal s ON u.csucursal_folio=s.folio
                                WHERE u.sta=1
                                AND s.sta=1
                                AND u.folio>1
                                ORDER BY u.nombre ASC  ";
						$resultado = $link->query($ssql);
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead>
                                <tr>
                                	<th>Acciones</th>
                                    <th>Folio</th>
                                    <th>Sucursal</th>    
                                    <th>Usuario</th>                                
                                    <th>Nombre</th>                                                                  
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php 
                            	if($resultado->num_rows>0){

                                    $resultado->data_seek(0);                           
                                    while($dt=$resultado->fetch_assoc()){
                            	?>
                                <tr id="reg-<?=$dt['folio']?>">
                                	<td>
                                		<ul class="table-controls">                                            
                                            <li><a href="#" class="btn tip accionGrid" title="Editar vendedores"><i class="fam-pencil"></i></a> </li>
                                            <li><a href="#" class="btn tip accionGrid" title="Eliminar vendedores"><i class="fam-cross"></i></a> </li>
                                        </ul>
                                	</td>
                                    <td><?=$dt['folio']?></td>
                                    <td><?=$dt['sucursal']?></td>    
                                    <td><?=$dt['usuario']?></td>    
                                    <td><?=$dt['nombre']?></td>                                                                       
                                    <td><?=$dt['email']?></td>                                   
                                    <td><?=$dt['telefono']?></td>                                                                                                           
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