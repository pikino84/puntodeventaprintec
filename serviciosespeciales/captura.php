<?php
	$accion = 'Crear Servicio Especial';
	$id=0;

	if($_GET){
		if($_GET['evt']=='U'&&isset($_GET['id'])){
			$id=intval($_GET['id']);
		}
	}

	$pagTitle = 'Printec - '.$accion;

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$urlBreadCrumbs = "<li><a href=\"/serviciosespeciales/\">Servicios Especiales</a></li>";
	$urlBreadCrumbs .= "<li><a href=\"/serviciosespeciales/captura.php?evt=I&id=0\">Crear Servicio Especial</a></li>";

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
			    			$precio = 0;
			    			$sku = '';
			    			$inventario=0;

			    			if($id>0){

			    				$ssql = "SELECT
		                                folio AS Folio,
		                                descrip AS Descripcion,
		                                precio AS Precio,
		                                promo AS Promo,
		                                moneda AS Moneda,
		                                sku AS Sku,
		                                existencia AS Existencia,
		                                CASE tipo
		                                WHEN 'N' THEN 'Normal'
		                                ELSE 'Especial'
		                                END AS Tipo,
		                                CASE WHEN IFNULL(Imagen,'')=''
		                                THEN 'http://puntodeventa.printec.mx/img/servicio-especial/servicio-especial.jpg'
		                                ELSE 
		                                CONCAT('http://puntodeventa.printec.mx/img/servicio-especial/',Imagen)
		                                END
		                                AS Imagen
		                                FROM cproducto
		                                WHERE sta=1
		                                AND tipo='E'
										AND folio='$id'";

								$resultado = $link->query($ssql);

								if($resultado->num_rows>0){

									$resultado->data_seek(0);
									$dt=$resultado->fetch_assoc();

					    			$nombre = $dt['Descripcion'];
					    			$precio = $dt['Precio'];
					    			$sku = $dt['Sku'];
					    			$inventario = $dt['Existencia'];

								}

			    			}

			    		?>
                 

                		<!-- Horizontal form -->
	                    <form id="frmProveedorTour" action="insertar.php" class="form-horizontal" enctype="multipart/form-data" method="post">
	                        <div class="widget">
	                            <div class="navbar"><div class="navbar-inner"><h6>Información del Servicio Especial</h6></div></div>
	                            <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="serviciosespeciales" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="<?=$id?>" />

	                            <div class="well">
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Descripción <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="nombre" name="nombre" value="<?=$nombre?>" placeholder="" maxlength="30" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Precio costo sin iva <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="precio" name="precio" value="<?=$precio?>" placeholder="" maxlength="30" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Sku <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="sku" name="sku" value="<?=$sku?>" placeholder="" maxlength="30" /></div>
	                                </div>  

	                                <div class="control-group">
	                                    <label class="control-label">Inventario <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="inventario" name="inventario" value="<?=$inventario?>" placeholder="" maxlength="30" /></div>
	                                </div> 

	                                <div class="control-group hide" id="vistaPrevia">
	                                	<label class="control-label">Vista previa</label>
	                                	<a class="lightbox" title="" href="<?=$dt['Imagen']?>"><img src="" width="80" height="80" /></a>
	                                </div>	                                
	                                
	                                <div class="control-group">
	                                    <label class="control-label">Logo</label>
	                                    <div class="controls"><input type="file" class="styled" id="imagen" name="imagen" value="" /></div>
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