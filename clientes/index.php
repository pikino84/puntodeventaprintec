<?php
	$pagTitle = "Catalogo de Clientes - Printec";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

    $urlBreadCrumbs = "<li><a href=\"/clientes/\">Clientes</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Listado de Clientes</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">   

                	<ul class="toolbar">
                        <li><a href="/clientes/captura.php?evt=I&id=0" title=""><i class="icon-user"></i><span>Crear Cliente</span></a></li>
                        <li><a href="/productos/" title=""><i class="icon-user"></i><span>Crear Cotización</span></a></li>
                        <li><a href="/clientes/exportar.php" target="blank" title=""><i class="icon-download-alt"></i><span>Exportar</span></a></li>
                    </ul>

                    <?php
                    	$ssql = "SELECT
                                a.folio,
                                a.nombre,
                                a.apellido,
                                a.email,
                                a.telefono,
                                a.cmembresia_folio,
                                a.ccanal_folio,
                                b.descrip AS membresia,
                                c.descrip AS canal,
                                case when ifnull(d.descrip,'') = '' then 'Sin Corporativo' else d.descrip end as corporativo
                                FROM ccliente a
                                INNER JOIN cmembresia b ON a.cmembresia_folio=b.folio
                                INNER JOIN ccanal c ON a.ccanal_folio=c.folio
                                LEFT JOIN ccorporativo d ON a.ccorporativo_folio=d.folio AND d.sta=1
                                WHERE a.sta=1
                                AND a.csucursal_folio=".$_SESSION['rtSucursal']."
                                AND b.sta=1
                                AND c.sta=1                                
                                ORDER BY a.folio DESC ";
						$resultado = $link->query($ssql);
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead>
                                <tr>
                                	<th>Acciones</th>
                                    <th>Folio</th>
                                    <th>Corporativo</th>
                                    <th>Nombre</th>       
                                    <th>Apellido</th>                                                                  
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <!-- <th>Membresia</th> -->
                                    <th>Canal</th>
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
                                            <li><a href="#" class="btn tip accionGrid" title="Editar clientes"><i class="fam-pencil"></i></a> </li>
                                            <li><a href="#" class="btn tip accionGrid" title="Eliminar clientes"><i class="fam-cross"></i></a> </li>
                                        </ul>
                                	</td>
                                    <td><?=$dt['folio']?></td>
                                    <td><?=$dt['corporativo']?></td>    
                                    <td><?=$dt['nombre']?></td>                                   
                                    <td><?=$dt['apellido']?></td>                                   
                                    <td><?=$dt['email']?></td>                                   
                                    <td><?=$dt['telefono']?></td>                                   
                                    <!-- <td><?=$dt['membresia']?></td>                                    -->
                                    <td><?=$dt['canal']?></td>
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