<?php
	$pagTitle = "Printec - Catalogo de Rangos";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

    $urlBreadCrumbs = "<li><a href=\"/rangos/\">Rangos</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Listado de Rangos</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">   

                    <ul class="toolbar">
                        <li><a href="/rangos/captura.php?evt=I&id=0" title=""><i class="icon-user"></i><span>Crear Rango</span></a></li>                        
                    </ul>

                	

                    <?php
                    	$ssql = "SELECT
                                                            folio,
                                                            rango_inicial as inicial,
                                                            rango_final as final
                                                            FROM crango
                                                            WHERE sta=1
                                                            ORDER BY rango_inicial ASC";
						$rs = mysql_query($ssql,$link);
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead>
                                <tr>       
                                    <th>Acciones</th>                         	
                                    <th>Folio</th>
                                    <th>Inicial</th>  
                                    <th>Final</th> 
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
                                            <li><a href="#" class="btn tip accionGrid" title="Eliminar rangos"><i class="fam-cross"></i></a> </li>
                                        </ul>
                                    </td>
                                	
                                    <td><?=$dt['folio']?></td>
                                    <td><?=$dt['inicial']?></td>   
                                    <td><?=$dt['final']?></td>
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