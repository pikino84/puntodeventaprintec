<?php
	$pagTitle = "Catalogo de Tintas - Printec";

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

    $urlBreadCrumbs = "<li><a href=\"/tinta/\">Tintas</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>

			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5>Listado de Tintas</h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->
                <div class="widget">   

                   
                	

                    <?php
                    	$ssql = "
                                SELECT
                                folio,
                                descrip,
                                sku
                                FROM cproducto
                                WHERE tipo='T'
                                AND sta=1
                                ORDER BY descrip ASC";
						$rs = mysql_query($ssql,$link);
                    ?>	

                    <div class="table-overflow">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead>
                                <tr>                                                           	
                                    <th>Folio</th>
                                    <th>Sku</th>
                                    <th>Nombre</th> 
                                </tr>
                            </thead>
                            <tbody>
                            	<?php 
                            	if(mysql_num_rows($rs)>0){
                            		while($dt=mysql_fetch_assoc($rs)){
                            	?>
                                <tr id="reg-<?=$dt['folio']?>">           
                                	
                                    <td><?=$dt['folio']?></td>
                                    <td><?=$dt['sku']?></td>   
                                    <td><?=$dt['descrip']?></td>
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