<?php
	$accion = 'Crear Cliente';
	$id=0;

	if($_GET){
		if($_GET['evt']=='U'&&isset($_GET['id'])){
			$id=intval($_GET['id']);
		}
	}

	$pagTitle = 'Printec - '.$accion;

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$urlBreadCrumbs = "<li><a href=\"/clientes/\">Clientes</a></li>";
	$urlBreadCrumbs .= "<li><a href=\"/clientes/captura.php?evt=I&id=0\">Crear Cliente</a></li>";

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
			    			$apellido = '';
			    			$cmembresia_folio = 0;
			    			$ccorporativo_folio=0;
			    			$ccanal_folio = 0;

			    			if($id>0){

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
		                                a.ccorporativo_folio
		                                FROM ccliente a
		                                INNER JOIN cmembresia b ON a.cmembresia_folio=b.folio
		                                INNER JOIN ccanal c ON a.ccanal_folio=c.folio
		                                WHERE a.sta=1
		                                AND b.sta=1
		                                AND c.sta=1
										AND a.folio='$id'";
								$resultado = $link->query($ssql);

								if($resultado->num_rows>0){

									$resultado->data_seek(0);
									$dt=$resultado->fetch_assoc();

					    			$nombre = $dt['nombre'];
					    			$tel = $dt['telefono'];
					    			$email = $dt['email'];
					    			$apellido = $dt['apellido'];
					    			$cmembresia_folio = $dt['cmembresia_folio'];
					    			$ccorporativo_folio = $dt['ccorporativo_folio'];
					    			$ccanal_folio = $dt['ccanal_folio'];	

								}

			    			}

			    		?>
                 

                		<!-- Horizontal form -->
	                    <form id="frmProveedorTour" action="insertar.php" class="form-horizontal" method="post">
	                        <div class="widget">

	                        	<ul class="toolbar">
			                        <li><a href="/corporativos/" title=""><i class="icon-user"></i><span>Corporativos</span></a></li>
			                        <li><a href="/productos/" title=""><i class="icon-shopping-cart"></i><span>Crear Cotización</span></a></li>
			                        <li><a href="/cotizaciones/" target="blank" title=""><i class="ico-search"></i><span>Cotizaciones</span></a></li>
			                    </ul>

	                            
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="clientes" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="<?=$id?>" />
	                            <input type="hidden" id="cmembresia_folio" name="cmembresia_folio" value="1" />

	                            <div class="well">
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Nombre <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="nombre" name="nombre" value="<?=$nombre?>" placeholder="" maxlength="250" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Apellido <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="apellido" name="apellido" value="<?=$apellido?>" placeholder="" maxlength="250" /></div>
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
	                                    <label class="control-label">Corporativo <span class="text-error">*</span></label>
	                                    <div class="controls">
	                                        <select id="ccorporativo_folio" name="ccorporativo_folio" class="validate[required] styled" data-prompt-position="topLeft:-1,-5">
		                                        <option value="0">NA</option>

		                                        <?php
				                                	//Obtener aeropuertos
									    			$ssql = "
															SELECT
															folio,
															descrip AS nom
															FROM ccorporativo
															WHERE sta=1
															AND csucursal_folio=".$_SESSION['rtSucursal']."
															ORDER BY descrip ASC ";
									    			$rsA = $link->query($ssql);

									    			if($rsA->num_rows>0){

													$rsA->data_seek(0);                          	
													while($dtA=$rsA->fetch_assoc()){

									    			if($dtA['folio']==$ccorporativo_folio){						    			
				                                ?>
		                                        <option value="<?=$dtA['folio']?>" selected="selected"><?=$dtA['nom']?></option>
		                                        <?php }else{?>
		                                        <option value="<?=$dtA['folio']?>"><?=$dtA['nom']?></option>	
		                                        <?php } }}?>
		                                    </select>
	                                    </div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Canal <span class="text-error">*</span></label>
	                                    <div class="controls">
	                                        <select id="ccanal_folio" name="ccanal_folio" class="validate[required] styled" data-prompt-position="topLeft:-1,-5">
		                                        <option value="0">NA</option>

		                                        <?php
				                                	//Obtener aeropuertos
									    			$ssql = "
															SELECT
															folio,
															descrip AS nom
															FROM ccanal
															WHERE sta=1
															AND csucursal_folio=".$_SESSION['rtSucursal']."
															ORDER BY descrip ASC ";
									    			$rsA = $link->query($ssql);

									    			if($rsA->num_rows>0){

													$rsA->data_seek(0);                          	
													while($dtA=$rsA->fetch_assoc()){

									    			if($dtA['folio']==$ccanal_folio){						    			
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