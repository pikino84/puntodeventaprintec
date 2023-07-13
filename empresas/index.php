<?php
	$accion = 'Listado de Empresas';
	$id=0;
	$tipo=0;

	if($_GET){
		if($_GET['evt']=='U'&&isset($_GET['id'])){
			$id=intval($_GET['id']);
			$accion = 'Modificar Empresas';
		}
	}

	$pagTitle = 'Printec - '.$accion;

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$listaJS[] = "/js/plugins/ui/jquery.fancybox.js";	

	$urlBreadCrumbs = "<li><a href=\"/empresas/\">Empresas</a></li>";

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/header.php');
?>


			    <!-- Page header -->
			    <div class="page-header">
			    	<div class="page-title">
				    	<h5><?=$accion?></h5>				    	
			    	</div>			    	
			    </div>
			    <!-- /page header -->

			    <!-- Default datatable -->

			    		
                 

                		

	                    <!-- Icons -->
                <div class="tabbable widget">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tabGaleria" data-toggle="tab">Empresas</a></li>
                    </ul>
                    <div class="tab-content">
                        
                  
			
			<div class="tab-pane active" id="tabGaleria">
                            <!--Aqui va el listado de imagenes del destino -->
                           

                            	<!-- Horizontal form -->
	                    <form id="frmDestinoImg" action="/empresas/insertar.php" class="form-horizontal" enctype="multipart/form-data" method="post">
	                        <div class="widget">	                            
	                            <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="empresas" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="0" />

	                            <div class="well">	                            	
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Nombre</label>
	                                    <div class="controls"><input type="text" class="span12" id="descrip" name="descrip" value="" placeholder="" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Email</label>
	                                    <div class="controls"><input type="text" class="span12" id="email" name="email" value="" placeholder="" /></div>
	                                </div>

	                                
	                                <div class="control-group hide" id="vistaPrevia">
	                                	<label class="control-label">Vista previa</label>
	                                	<a class="lightbox" title="" href=""><img src="" width="80" height="80" /></a>
	                                </div>	                                
	                                
	                                <div class="control-group">
	                                    <label class="control-label">Logo</label>
	                                    <div class="controls"><input type="file" class="styled" id="imagen" name="imagen" value="" /></div>
	                                </div>	                                
	                                                               
	                                
	                                <div class="form-actions align-right">
	                                    <button type="submit" class="btn btn-primary">Guardar</button>	                                    
	                                    <button type="reset" class="btn">Limpiar</button>
	                                </div>

	                            </div>
	                            
	                        </div>
	                    </form>
	                    <!-- /horizontal form -->


                            	<?php
		                    	$ssql = "SELECT
										folio AS folio,
										email as email,
										logo_cotizacion AS img,
										nombre AS descrip
										FROM cempresa
										WHERE sta=1
										ORDER BY folio DESC  ";
								
								$resultado = $link->query($ssql);

								if($resultado->num_rows>0){
								?>

								<table class="table table-striped table-bordered table-destino-img">
		                            <thead>
		                                <tr>
		                                	<th>Acciones</th>
		                                    <th>Folio</th>  
		                                    <th>Nombre</th>
		                                    <th>Email</th>
		                                    <th>Logo</th>
		                                </tr>
		                            </thead>
		                            <tbody>
		                            	<?php 		  
		                            	    $resultado->data_seek(0);                          	
		                            		while($dt=$resultado->fetch_assoc()){
		                            	?>
		                                <tr id="reg-<?=$dt['folio']?>">
		                                	<td>
		                                		<ul class="table-controls">                                            
		                                            <li><a href="#" class="btn tip accionGridInt" title="Editar empresas"><i class="fam-pencil"></i></a> </li>
		                                            <li><a href="#" class="btn tip accionGrid" title="Eliminar empresas"><i class="fam-cross"></i></a> </li>
		                                        </ul>
		                                	</td>
		                                    <td><?=$dt['folio']?></td>
		                                    <td><?=$dt['descrip']?></td>  
		                                    <td><?=$dt['email']?></td>
		                                    <td><a class="lightbox" title="" href="/img/<?=$dt['img']?>"><img src="/img/<?=$dt['img']?>" width="80" height="80" /></a></td>
		                                </tr>
		                                <?php } ?>
		                            </tbody>
		                        </table>

								<?php
								}else{
									echo "No se encontraron registros";
								}

							
		                    ?>
                            <!--Fin el listado de imagenes del destino -->
                        </div>
                    </div>
                </div>
                <!-- /icons -->


                	
                
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