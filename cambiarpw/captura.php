<?php
	$accion = 'Cambiar contraseña';
	$id=0;

	if($_GET){
		if($_GET['evt']=='U'&&isset($_GET['id'])){
			$id=intval($_GET['id']);
		}
	}

	$pagTitle = 'Printec - '.$accion;

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$urlBreadCrumbs = "<li><a href=\"/cotizaciones/pendientes.php\">Cotizaciones pendientes</a></li>";
	$urlBreadCrumbs .= "<li><a href=\"/productos/\">Crear Cotización</a></li>";

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
			    			
			    			$pw = '';

			    		?>
                 

                		<!-- Horizontal form -->
	                    <form id="frmProveedorTour" action="insertar.php" class="form-horizontal" method="post">
	                        <div class="widget">

	                        	<ul class="toolbar">
			                        <li><a href="/empresas/" title=""><i class="icon-user"></i><span>Empresas</span></a></li>
			                        <li><a href="/productos/" title=""><i class="icon-shopping-cart"></i><span>Crear Cotización</span></a></li>
			                        <li><a href="/cotizaciones/" target="blank" title=""><i class="ico-search"></i><span>Cotizaciones</span></a></li>
			                    </ul>

	                            
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="vendedores" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="<?=$id?>" />

	                            <div class="well">

	                                <div class="control-group">
	                                    <label class="control-label">Usuario <span class="text-error">*</span></label>
	                                    <div class="controls"><?=$_SESSION['rtLogin']?></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Contraseña <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="pw" name="pw" value="<?=$pw?>" placeholder="" maxlength="20" /></div>
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