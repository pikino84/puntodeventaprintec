<?php
	$pagTitle = "Printec - Catalogo de Canales";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

    $urlBreadCrumbs = "<li><a href=\"/canales/\">Canales</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Listado de Canales</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">   

                    <ul class="toolbar">
                        <li><a href="/canales/captura.php?evt=I&id=0" title=""><i class="icon-user"></i><span>Crear Canal</span></a></li>                        
                    </ul>

                	

                    <?php
                    	$ssql = "SELECT
                                                            folio,
                                                            descrip AS nom
                                                            FROM ccanal
                                                            WHERE sta=1
                                                            AND csucursal_folio=".$_SESSION['rtSucursal']."
                                                            ORDER BY descrip ASC";
						$rs = mysql_query($ssql,$link);
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead>
                                <tr>       
                                    <th>Acciones</th>                         	
                                    <th>Folio</th>
                                    <th>Descripción</th>  
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
                                            <li><a href="#" class="btn tip accionGrid" title="Editar canales"><i class="fam-pencil"></i></a> </li>
                                            <li><a href="#" class="btn tip accionGrid" title="Eliminar canales"><i class="fam-cross"></i></a> </li>
                                        </ul>
                                    </td>
                                	
                                    <td><?=$dt['folio']?></td>
                                    <td><?=$dt['nom']?></td>   
                                </tr>
                                <?php } }else{?>
                                <tr><td colspan="3">No existen registros</td></tr>
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