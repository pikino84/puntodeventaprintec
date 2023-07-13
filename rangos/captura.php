<?php
	$accion = 'Crear Rango';
	$id=0;

	if($_GET){
		if($_GET['evt']=='U'&&isset($_GET['id'])){
			$id=intval($_GET['id']);
		}
	}

	$pagTitle = 'Printec - '.$accion;

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$urlBreadCrumbs = "<li><a href=\"/rangos/\">Rangos</a></li>";
	$urlBreadCrumbs .= "<li><a href=\"/rangos/captura.php?evt=I&id=0\">Crear Rango</a></li>";

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

			    			$inicial = 0;
			    			$final=0;

			    			if($id>0){

			    				$ssql = "SELECT
                                                            folio,
                                                            rango_inicial as inicial,
                                                            rango_final as final
                                                            FROM crango
                                                            WHERE sta=1
															AND folio='$id'";
								$rs = mysql_query($ssql,$link);

								if(mysql_num_rows($rs)>0){
									$dt = mysql_fetch_assoc($rs);

					    			$inicial = $dt['inicial'];	
					    			$final = $dt['final'];

								}

			    			}

			    		?>
                 

                		<!-- Horizontal form -->
	                    <form id="frmProveedorTour" action="insertar.php" class="form-horizontal" method="post">
	                        <div class="widget">
	                            <div class="navbar"><div class="navbar-inner"><h6>Información del Rango</h6></div></div>
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="rangos" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="<?=$id?>" />

	                            <div class="well">
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Inicial <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="number" class="span12" id="inicial" name="inicial" value="<?=$inicial?>" placeholder="" maxlength="12" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Final <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="number" class="span12" id="final" name="final" value="<?=$final?>" placeholder="" maxlength="12" /></div>
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