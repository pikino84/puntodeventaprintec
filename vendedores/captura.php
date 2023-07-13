<?php
	$accion = 'Crear Vendedor';
	$id=0;

	if($_GET){
		if($_GET['evt']=='U'&&isset($_GET['id'])){
			$id=intval($_GET['id']);
		}
	}

	$pagTitle = 'Printec - '.$accion;

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$urlBreadCrumbs = "<li><a href=\"/vendedores/\">Vendedores</a></li>";
	$urlBreadCrumbs .= "<li><a href=\"/vendedores/captura.php?evt=I&id=0\">Crear Vendedor</a></li>";

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
			    			$tel = '';
			    			$email = '';			    			
			    			$csucursal_folio = 0;
			    			$usuario = '';
			    			$pw = '';

			    			if($id>0){

			    				$ssql = "
			    				SELECT
                                u.folio AS folio,
                                u.nombre AS nombre,
                                u.usuario AS usuario,
                                u.pw AS pw,
                                u.csucursal_folio AS csucursal_folio,
                                u.email AS email,
                                u.telefono AS telefono,
                                s.nombre AS sucursal
                                FROM cusuario u
                                INNER JOIN csucursal s ON u.csucursal_folio=s.folio
                                WHERE u.sta=1
                                AND s.sta=1
                                AND u.folio='$id'                              
										";
								$resultado = $link->query($ssql);

								if($resultado->num_rows>0){

									$resultado->data_seek(0);
									$dt=$resultado->fetch_assoc();

					    			$nombre = $dt['nombre'];
					    			$tel = $dt['telefono'];
					    			$email = $dt['email'];
					    			$csucursal_folio = $dt['csucursal_folio'];
					    			$usuario = $dt['usuario'];	

								}

			    			}

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
	                                    <label class="control-label">Nombre <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="nombre" name="nombre" value="<?=$nombre?>" placeholder="" maxlength="250" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Usuario <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="usuario" name="usuario" value="<?=$usuario?>" placeholder="" maxlength="20" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Contraseña <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="password" class="span12" id="pw" name="pw" value="<?=$pw?>" placeholder="" maxlength="20" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Teléfono <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="tel" name="tel" value="<?=$tel?>" placeholder="" maxlength="30" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Email <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="email" name="email" value="<?=$email?>" placeholder="" maxlength="250" /></div>
	                                </div>	                                

	                                <div class="control-group">
	                                    <label class="control-label">Sucursal <span class="text-error">*</span></label>
	                                    <div class="controls">
	                                        <select id="csucursal_folio" name="csucursal_folio" class="validate[required] styled" data-prompt-position="topLeft:-1,-5">
		                                        <option value="0">NA</option>

		                                        <?php
				                                	//Obtener aeropuertos
									    			$ssql = "
															SELECT
															folio,
															nombre AS nom
															FROM csucursal
															WHERE sta=1															
															ORDER BY nombre ASC ";
									    			$rsA = $link->query($ssql);

									    			if($rsA->num_rows>0){

														$rsA->data_seek(0);                          	
														while($dtA=$rsA->fetch_assoc()){

									    			if($dtA['folio']==$csucursal_folio){						    			
				                                ?>
		                                        <option value="<?=$dtA['folio']?>" selected="selected"><?=$dtA['nom']?></option>
		                                        <?php }else{?>
		                                        <option value="<?=$dtA['folio']?>"><?=$dtA['nom']?></option>	
		                                        <?php } }}?>
		                                    </select>
	                                    </div>
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