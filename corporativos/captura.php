<?php
	$accion = 'Crear Corporativo';
	$id=0;

	if($_GET){
		if($_GET['evt']=='U'&&isset($_GET['id'])){
			$id=intval($_GET['id']);
		}
	}

	$pagTitle = 'Printec - '.$accion;

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$urlBreadCrumbs = "<li><a href=\"/corporativos/\">Corporativos</a></li>";
	$urlBreadCrumbs .= "<li><a href=\"/corporativos/captura.php?evt=I&id=0\">Crear Corporativo</a></li>";

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

			    		<?php

			    			$nombre = '';

			    			if($id>0){

			    				$ssql = "SELECT
                                                            folio,
                                                            descrip AS nom
                                                            FROM ccorporativo
                                                            WHERE sta=1
										AND folio='$id'";
								$resultado = $link->query($ssql);

								if($resultado->num_rows>0){

									$resultado->data_seek(0);
									$dt=$resultado->fetch_assoc();									

					    			$nombre = $dt['nom'];	

								}

			    			}

			    		?>
                 

                		<!-- Horizontal form -->
	                    <form id="frmProveedorTour" action="insertar.php" class="form-horizontal" method="post">
	                        <div class="widget">
	                            <div class="navbar"><div class="navbar-inner"><h6>Información del Corporativo</h6></div></div>
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="corporativos" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="<?=$id?>" />

	                            <div class="well">
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Descripción <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="nombre" name="nombre" value="<?=$nombre?>" placeholder="" maxlength="30" /></div>
	                                </div>
	                                                               
	                                
	                                <div class="form-actions align-right">
	                                    <button type="submit" class="btn btn-primary">Guardar</button>
	                                    <button type="button" class="btn btn-danger accionForm">Cancelar</button>
	                                    <button type="reset" class="btn">Limpiar</button>
	                                </div>

	                            </div>
	                            
	                        </div>
	                    </form>
	                    <!-- /horizontal form -->

                	
                
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