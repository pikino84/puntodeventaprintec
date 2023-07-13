<?php
	$accion = 'Crear Sucursal';
	$id=0;

	if($_GET){
		if($_GET['evt']=='U'&&isset($_GET['id'])){
			$id=intval($_GET['id']);
		}
	}

	$pagTitle = 'Printec - '.$accion;

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$urlBreadCrumbs = "<li><a href=\"/sucursales/\">Sucursales</a></li>";
	$urlBreadCrumbs .= "<li><a href=\"/sucursales/captura.php?evt=I&id=0\">Crear Sucursal</a></li>";

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
			    			$cmembresia_folio = 0;
			    			$cempresa_folio=0;
			    			$cuenta = '';
			    			$banco = '';
			    			$clabe = '';
			    			$facebook = '';
			    			$twitter = '';
			    			$sitio = '';
			    			$num_tarjeta='';
			    			$aplica_servicioespecial=0;
			    			$rfc='';

			    			if($id>0){

			    				$ssql = "SELECT 
                                s.folio,
                                s.nombre,
                                s.email,
                                s.telefono AS telefono,
                                e.nombre AS empresa,
                                m.descrip AS membresia,
                                s.cempresa_folio AS cempresa_folio,
                                s.cmembresia_folio AS cmembresia_folio,
                                s.banco AS banco,
                                s.cuenta AS cuenta,
                                s.clabe AS clabe,
                                s.facebook as facebook,
                                s.twitter as twitter,
                                s.sitio as sitio,
                                s.num_tarjeta as num_tarjeta,
                                s.rfc as rfc,
                                ifnull(s.aplica_servicioesp,0) as aplica_servicioesp
                                FROM csucursal s
                                INNER JOIN cempresa e ON s.cempresa_folio=e.folio
                                INNER JOIN cmembresia m ON s.cmembresia_folio=m.folio
                                WHERE s.sta=1
                                AND e.sta=1
                                AND m.sta=1
                                AND s.folio='$id'                                
										";
								$resultado = $link->query($ssql);

								if($resultado->num_rows>0){

									$resultado->data_seek(0);  
									$dt=$resultado->fetch_assoc();

					    			$nombre = $dt['nombre'];
					    			$tel = $dt['telefono'];
					    			$email = $dt['email'];
					    			$cmembresia_folio = $dt['cmembresia_folio'];
					    			$cempresa_folio = $dt['cempresa_folio'];
					    			$cuenta = $dt['cuenta'];	
					    			$banco = $dt['banco'];
					    			$clabe = $dt['clabe'];
					    			$facebook = $dt['facebook'];	
					    			$twitter = $dt['twitter'];
					    			$sitio = $dt['sitio'];
					    			$num_tarjeta= $dt['num_tarjeta'];
					    			$aplica_servicioespecial= $dt['aplica_servicioesp'];
					    			$rfc= $dt['rfc'];

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

	                            
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="sucursales" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="<?=$id?>" />
	                            <input type="hidden" id="cmembresia_folio" name="cmembresia_folio" value="1" />

	                            <div class="well">
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Nombre <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="nombre" name="nombre" value="<?=$nombre?>" placeholder="" maxlength="250" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">RFC <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="rfc" name="rfc" value="<?=$rfc?>" placeholder="" maxlength="50" /></div>
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
	                                    <label class="control-label">Empresa <span class="text-error">*</span></label>
	                                    <div class="controls">
										<select id="cempresa_folio" name="cempresa_folio" class="validate[required] styled" data-prompt-position="topLeft:-1,-5">
											<option value="0">NA</option>
											<?php
											// Obtener aeropuertos
											$ssql = "SELECT folio, nombre AS nom FROM cempresa WHERE sta = 1 ORDER BY nombre ASC";
											$rsA = $link->query($ssql);

											if ($rsA->num_rows > 0) {
												$rsA->data_seek(0);
												while ($dt = $rsA->fetch_assoc()) {
													if ($dt['folio'] == $cempresa_folio) {
														?>
														<option value="<?= $dt['folio'] ?>" selected="selected"><?= $dt['nom'] ?></option>
													<?php
													} else {
														?>
														<option value="<?= $dt['folio'] ?>"><?= $dt['nom'] ?></option>
													<?php
													}
												}
											}
											?>
										</select>
	                                    </div>
	                                </div>
	                                <div class="control-group">
	                                    <label class="control-label">Membresía <span class="text-error">*</span></label>
	                                    <div class="controls">
											<select id="cmembresia_folio" name="cmembresia_folio" class="validate[required] styled" data-prompt-position="topLeft:-1,-5">
												<option value="0">NA </option>
												<?php
												// Obtener aeropuertos
												$ssql = "SELECT folio, descrip AS nom FROM cmembresia WHERE sta = 1 ORDER BY descrip ASC";
												$rsA = $link->query($ssql);
												if ($rsA->num_rows > 0) {
													$rsA->data_seek(0);
													while ($dt = $rsA->fetch_assoc()) {
														if ($dt['folio'] == $cmembresia_folio) {
															?>
															<option value="<?= $dt['folio'] ?>" selected="selected"><?= $dt['nom'] ?></option>
														<?php
														} else {
															?>
															<option value="<?= $dt['folio'] ?>"><?= $dt['nom'] ?></option>
														<?php
														}
													}
												}
												?>
											</select>
	                                    </div>
	                                </div>
	                                <div class="control-group">
	                                    <label class="control-label">Banco <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="banco" name="banco" value="<?=$banco?>" placeholder="" maxlength="250" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Cuenta <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="cuenta" name="cuenta" value="<?=$cuenta?>" placeholder="" maxlength="30" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Clabe <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="clabe" name="clabe" value="<?=$clabe?>" placeholder="" maxlength="30" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Número de tarjeta <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="num_tarjeta" name="num_tarjeta" value="<?=$num_tarjeta?>" placeholder="" maxlength="16" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Facebook <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="facebook" name="facebook" value="<?=$facebook?>" placeholder="" maxlength="250" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Twitter <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="twitter" name="twitter" value="<?=$twitter?>" placeholder="" maxlength="30" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Pagina web <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="sitio" name="sitio" value="<?=$sitio?>" placeholder="" maxlength="250" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">¿Aplica Servicios Especiales?:</label>
	                                    <div class="controls">
	                                        <label class="checkbox inline"><input type="checkbox" id="aplica_servicioespecial" name="aplica_servicioespecial" value="1" <?php if($aplica_servicioespecial=='1'){?> checked="checked" <?php }?> ></label>
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