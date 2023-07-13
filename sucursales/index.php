<?php
	$pagTitle = "Catalogo de Sucursales - Printec";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

    $urlBreadCrumbs = "<li><a href=\"/sucursales/\">Sucursales</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Listado de Sucursales</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">   

                	<ul class="toolbar">
                        <li><a href="/sucursales/captura.php?evt=I&id=0" title=""><i class="icon-user"></i><span>Crear Sucursal</span></a></li>
                        <li><a href="/productos/" title=""><i class="icon-user"></i><span>Crear Cotización</span></a></li>
                    </ul>

                    <?php
                    	$ssql = "SELECT 
                                s.folio,
                                s.nombre,
                                s.email,
                                s.telefono AS telefono,
                                e.nombre AS empresa,
                                m.descrip AS membresia,
                                s.cempresa_folio AS cempresa_folio,
                                s.cmembresia_folio AS cmembresia_folio,
                                s.banco AS banco,
                                s.cuenta AS cuenta,
                                s.clabe AS clabe
                                FROM csucursal s
                                INNER JOIN cempresa e ON s.cempresa_folio=e.folio
                                INNER JOIN cmembresia m ON s.cmembresia_folio=m.folio
                                WHERE s.sta=1
                                AND e.sta=1
                                AND m.sta=1
                                ORDER BY s.nombre ASC  ";
						$resultado = $link->query($ssql);
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead>
                                <tr>
                                	<th>Acciones</th>
                                    <th>Folio</th>
                                    <th>Empresa</th>
                                    <th>Membresía</th>
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
                                            <li><a href="#" class="btn tip accionGrid" title="Editar sucursales"><i class="fam-pencil"></i></a> </li>
                                            <li><a href="#" class="btn tip accionGrid" title="Eliminar sucursales"><i class="fam-cross"></i></a> </li>
                                        </ul>
                                	</td>
                                    <td><?=$dt['folio']?></td>
                                    <td><?=$dt['empresa']?></td>    
                                    <td><?=$dt['membresia']?></td>    
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