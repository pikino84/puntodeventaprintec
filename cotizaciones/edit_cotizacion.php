<?php
	$accion = 'Edicion de la cotización';
	$id=0;

	if($_GET){
		if($_GET['evt']=='U'&&isset($_GET['id'])){
			$id=intval($_GET['id']);
		}
	}

	$pagTitle = $accion.' - Printec';

	//$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$urlBreadCrumbs = "<li><a href=\"/cotizaciones/\">Cotizaciones</a></li>";	

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
			    			$folio=0;
			    			$nombre = '';							
							$factura = '';	
							$fecha = '';								
							$cstatusreserva_folio=0;
							$cstatuspago_folio =0;
							$envio=0;
							$requierelogo=0;
							$dias=0;
							$copiacorporativo=0;
							$asunto='';
							$ccliente_folio=0;
							$interna=0;
							$incluye_iva=1;
							$incluye_urgencia=0;

			    			if($id>0){

			    				$ssql = "SELECT
				                                a.folio,
				                                CONCAT(b.nombre,' ',b.apellido) AS cliente,				                                
				                                CONCAT(SUBSTRING(a.fecha,7,2),'/',SUBSTRING(a.fecha,5,2),'/',SUBSTRING(a.fecha,1,4)) as fecha,
				                                a.hora,
				                                d.descrip AS estatuscotizacion,
				                                e.descrip AS estatuspagocliente,
				                                d.clase AS claseestatuscotizacion,
				                                e.clase AS claseestatuspagocliente,
				                                a.cestatuscotizacion_folio,
				                                a.cestatuspagocliente_folio,
				                                a.factura,
				                                a.cerrado,
				                                ifnull(a.incluye_envio,0) as incluye_envio,
				                                ifnull(a.requiere_logo,0) as requiere_logo,
				                                ifnull(a.dias_entrega,15) as dias_entrega,
				                                ifnull(a.copiacorporativo,0) as copiacorporativo,
				                                ifnull(a.asunto,'') as asunto,
				                                IFNULL(a.ccliente_folio,0) AS ccliente_folio,
				                                ifnull(a.interna,0) as interna,
				                                ifnull(a.incluye_iva,1) as incluye_iva,
				                                ifnull(a.incluye_urgencia,0) as incluye_urgencia
				                                FROM ccotizacion a
				                                LEFT JOIN ccliente b ON a.ccliente_folio=b.folio AND b.sta=1
				                                INNER JOIN cusuario c ON a.cusuario_folio=c.folio				                                
				                                INNER JOIN cestatuscotizacion d ON a.cestatuscotizacion_folio=d.folio
				                                INNER JOIN cestatuspagocliente e ON a.cestatuspagocliente_folio=e.folio
				                                WHERE a.sta=1				                                
				                                AND c.sta=1
				                                AND d.sta=1
				                                AND e.sta=1  
                                                AND a.folio='$id' ";
								$resultado = $link->query($ssql);

								if($resultado->num_rows>0){

									$resultado->data_seek(0);
									$dt=$resultado->fetch_assoc();

									$folio = $dt['folio'];
									$nombre = $dt['cliente'];
									$factura = $dt['factura'];
									$fecha = $dt['fecha'].' '.$dt['hora'];								
									$cstatusreserva_folio=$dt['cestatuscotizacion_folio'];
									$cstatuspago_folio =$dt['cestatuspagocliente_folio'];
									$cw = $dt['cerrado'];
									$envio=$dt['incluye_envio'];
									$requierelogo=$dt['requiere_logo'];
									$dias=$dt['dias_entrega'];
									$copiacorporativo=$dt['copiacorporativo'];
									$asunto=$dt['asunto'];
									$ccliente_folio=$dt['ccliente_folio'];
									$interna=$dt['interna'];
									$incluye_iva=$dt['incluye_iva'];
									$incluye_urgencia=$dt['incluye_urgencia'];

								}								

			    			}

			    		?>
                 
			    		<?php if($_SESSION['rtSuperAdmin']==1){ ?>
                		<!-- Horizontal form -->
	                    <form id="frmReservaciones" action="cambiar-estatus.php" class="form-horizontal" method="post">
	                        <div class="widget">
	                            <div class="navbar">
	                            	<div class="navbar-inner"><h6>Información</h6>
	                            	<h6><a href="detalle.php?id=<?=$folio?>&evt=V&cw=<?=$cw?>" >Ver cotizacion</a></h6></div>
	                            </div>
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="cotizaciones" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="<?=$id?>" />

	                            <div class="well">

	                            	<div class="control-group">
	                                    <label class="control-label">Cotizacion </label>
	                                    <div class="controls"><strong>PRINT<?=$id?></strong></div>
	                                </div>
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Cliente </label>
	                                    <div class="controls">
	                                    	<select id="cbocliente" name="cbocliente" class="validate[required] styled" data-prompt-position="topLeft:-1,-5"> 
	                                    		<option value="0">Cliente no asignado</option>
		                                        <?php
				                                	//Obtener pais
									    			$ssql = "
															SELECT
													        a.folio AS Folio,
													        concat(a.apellido,' ',a.nombre) AS Nombre,
													        a.email AS Email,
													        a.telefono AS Telefono,
													        a.ccanal_folio AS CanalFolio,
													        c.descrip AS Canal,
													        CASE WHEN IFNULL(d.descrip,'') = '' THEN 'Sin Corporativo' ELSE d.descrip END AS Corporativo,
													        s.nombre AS Sucursal
													        FROM ccliente a
													        INNER JOIN csucursal s ON a.csucursal_folio=s.folio
													        INNER JOIN ccanal c ON a.ccanal_folio=c.folio
													        LEFT JOIN ccorporativo d ON a.ccorporativo_folio=d.folio AND d.sta=1
													        WHERE a.sta=1
													        AND c.sta=1
													        AND s.sta=1
													        AND s.folio='".$_SESSION['rtSucursal']."'                             
													        ORDER BY a.apellido,a.nombre asc
															";
									    			$rsA = $link->query($ssql);

									    			if($rsA->num_rows>0){

														$rsA->data_seek(0);                          	
														while($dtA=$rsA->fetch_assoc()){

									    			if($dtA['Folio']==$ccliente_folio){						    			
				                                ?>
		                                        <option value="<?=$dtA['Folio']?>" selected="selected"><?="[".$dtA['Folio']."] ".$dtA['Nombre']?></option>
		                                        <?php }else{?>
		                                        <option value="<?=$dtA['Folio']?>"><?="[".$dtA['Folio']."] ".$dtA['Nombre']?></option>	
		                                        <?php } }}?>
		                                    </select>
	                                    </div>
	                                </div> 
	                                
	                                <div class="control-group">
	                                    <label class="control-label">StatusCotizacion <span class="text-error">*</span></label>
	                                    <div class="controls">
	                                        <select id="statusreserva" name="statusreserva" class="validate[required] styled" data-prompt-position="topLeft:-1,-5"> 
		                                        <?php
				                                	//Obtener pais
									    			$ssql = "
															SELECT
															folio,
															descrip as nom
															FROM cestatuscotizacion
															WHERE sta=1
															ORDER BY descrip ASC 
															";
									    			$rsA = $link->query($ssql);

									    			if($rsA->num_rows>0){

														$rsA->data_seek(0);                          	
														while($dtA=$rsA->fetch_assoc()){

									    			if($dtA['folio']==$cstatusreserva_folio){						    			
				                                ?>
		                                        <option value="<?=$dtA['folio']?>" selected="selected"><?=$dtA['nom']?></option>
		                                        <?php }else{?>
		                                        <option value="<?=$dtA['folio']?>"><?=$dtA['nom']?></option>	
		                                        <?php } }}?>
		                                    </select>
	                                    </div>
	                                </div>   

	                                <div class="control-group">
	                                    <label class="control-label">StatusPagoCliente <span class="text-error">*</span></label>
	                                    <div class="controls">
	                                        <select id="statuspagocliente" name="statuspagocliente" class="validate[required] styled" data-prompt-position="topLeft:-1,-5">
		                                        

		                                        <?php
				                                	//Obtener pais
									    			$ssql = "
															SELECT
															folio,
															descrip as nom
															FROM cestatuspagocliente 
															WHERE sta=1
															ORDER BY descrip ASC 
															";
									    			$rsA = $link->query($ssql);

									    			if($rsA->num_rows>0){

														$rsA->data_seek(0);                          	
														while($dtA=$rsA->fetch_assoc()){

									    			if($dtA['folio']==$cstatuspago_folio){						    			
				                                ?>
		                                        <option value="<?=$dtA['folio']?>" selected="selected"><?=$dtA['nom']?></option>
		                                        <?php }else{?>
		                                        <option value="<?=$dtA['folio']?>"><?=$dtA['nom']?></option>	
		                                        <?php } }}?>
		                                    </select>
	                                    </div>
	                                </div>         

	                                <div class="control-group">
	                                    <label class="control-label">Factura</label>
	                                    <div class="controls"><input type="text" class="span12" id="factura" name="factura" value="<?=$factura?>" placeholder="" maxlength="30" /></div>
	                                </div>

	                                 <div class="control-group">
	                                    <label class="control-label">Interna:</label>
	                                    <div class="controls">
	                                        <label class="checkbox inline"><input type="checkbox" id="interna" name="interna" value="1" <?php if($interna=='1'){?> checked="checked" <?php }?> ></label>
	                                    </div>
	                                </div>	

	                                <div class="control-group">
	                                    <label class="control-label">Enviar copia a corporativo:</label>
	                                    <div class="controls">
	                                        <label class="checkbox inline"><input type="checkbox" id="copiacorporativo" name="copiacorporativo" value="1" <?php if($copiacorporativo=='1'){?> checked="checked" <?php }?> ></label>
	                                    </div>
	                                </div>	  

	                                <div class="control-group">
	                                    <label class="control-label">Incluye envió:</label>
	                                    <div class="controls">
	                                        <label class="checkbox inline"><input type="checkbox" id="envio" name="envio" value="1" <?php if($envio=='1'){?> checked="checked" <?php }?> ></label>
	                                    </div>
	                                </div>	

	                                <div class="control-group">
	                                    <label class="control-label">Incluye Urgencia en Entrega:</label>
	                                    <div class="controls">
	                                        <label class="checkbox inline"><input type="checkbox" id="urgencia" name="urgencia" value="1" <?php if($incluye_urgencia=='1'){?> checked="checked" <?php }?> ></label>
	                                    </div>
	                                </div>	

	                                <div class="control-group">
	                                    <label class="control-label">Incluye IVA:</label>
	                                    <div class="controls">
	                                        <label class="checkbox inline"><input type="checkbox" id="iva" name="iva" value="1" <?php if($incluye_iva==1){?> checked="checked" <?php }?> ></label>
	                                    </div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Requiere Logo en Vectores:</label>
	                                    <div class="controls">
	                                        <label class="checkbox inline"><input type="checkbox" id="requierelogo" name="requierelogo" value="1" <?php if($requierelogo=='1'){?> checked="checked" <?php }?> ></label>
	                                    </div>
	                                </div>	

	                                <div class="control-group">
	                                    <label class="control-label">Días entrega</label>
	                                    <div class="controls"><input type="text" class="span12" id="dias" name="dias" value="<?=$dias?>" placeholder="" maxlength="30" /></div>
	                                </div>   

	                                <div class="control-group">
	                                    <label class="control-label">Asunto</label>
	                                    <div class="controls"><input type="text" class="span12" id="asunto" name="asunto" value="<?=$asunto?>" placeholder="" maxlength="350" /></div>
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
		                <?php } else{?>

		                	<!-- Horizontal form -->
	                    <form id="frmReservaciones" action="cambiar-estatus-externo.php" class="form-horizontal" method="post">
	                        <div class="widget">
	                            <div class="navbar">
	                            	<div class="navbar-inner"><h6>Información</h6>
	                            	<h6><a href="detalle.php?id=<?=$folio?>&evt=V&cw=<?=$cw?>" >Ver cotizacion</a></h6></div>
	                            </div>
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="cotizaciones" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="<?=$id?>" />

	                            <div class="well">

	                            	<div class="control-group">
	                                    <label class="control-label">Cotizacion </label>
	                                    <div class="controls"><strong>PRINT<?=$id?></strong></div>
	                                </div>
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Cliente </label>
	                                    <div class="controls">
	                                    	<select id="cbocliente" name="cbocliente" class="validate[required] styled" data-prompt-position="topLeft:-1,-5"> 
	                                    		<option value="0">Cliente no asignado</option>
		                                        <?php
				                                	//Obtener pais
									    			$ssql = "
															SELECT
													        a.folio AS Folio,
													        concat(a.apellido,' ',a.nombre) AS Nombre,
													        a.email AS Email,
													        a.telefono AS Telefono,
													        a.ccanal_folio AS CanalFolio,
													        c.descrip AS Canal,
													        CASE WHEN IFNULL(d.descrip,'') = '' THEN 'Sin Corporativo' ELSE d.descrip END AS Corporativo,
													        s.nombre AS Sucursal
													        FROM ccliente a
													        INNER JOIN csucursal s ON a.csucursal_folio=s.folio
													        INNER JOIN ccanal c ON a.ccanal_folio=c.folio
													        LEFT JOIN ccorporativo d ON a.ccorporativo_folio=d.folio AND d.sta=1
													        WHERE a.sta=1
													        AND c.sta=1
													        AND s.sta=1
													        AND s.folio='".$_SESSION['rtSucursal']."'                             
													        ORDER BY a.apellido,a.nombre asc
															";
									    			$rsA = $link->query($ssql);

									    			if($rsA->num_rows>0){

														$rsA->data_seek(0);                          	
														while($dtA=$rsA->fetch_assoc()){

									    			if($dtA['Folio']==$ccliente_folio){						    			
				                                ?>
		                                        <option value="<?=$dtA['Folio']?>" selected="selected"><?="[".$dtA['Folio']."] ".$dtA['Nombre']?></option>
		                                        <?php }else{?>
		                                        <option value="<?=$dtA['Folio']?>"><?="[".$dtA['Folio']."] ".$dtA['Nombre']?></option>	
		                                        <?php } }}?>
		                                    </select>
	                                    </div>
	                                </div> 
	                                
	                                <div class="control-group">
	                                    <label class="control-label">StatusCotizacion <span class="text-error">*</span></label>
	                                    <div class="controls">
	                                        <select id="statusreserva" name="statusreserva" class="validate[required] styled" data-prompt-position="topLeft:-1,-5"> 
		                                        <?php
				                                	//Obtener pais
									    			$ssql = "
															SELECT
															folio,
															descrip as nom
															FROM cestatuscotizacion
															WHERE sta=1
															AND folio in (1,4)
															ORDER BY descrip ASC 
															";
									    			$rsA = $link->query($ssql);

									    			if($rsA->num_rows>0){

														$rsA->data_seek(0);                          	
														while($dtA=$rsA->fetch_assoc()){

									    			if($dtA['folio']==$cstatusreserva_folio){						    			
				                                ?>
		                                        <option value="<?=$dtA['folio']?>" selected="selected"><?=$dtA['nom']?></option>
		                                        <?php }else{?>
		                                        <option value="<?=$dtA['folio']?>"><?=$dtA['nom']?></option>	
		                                        <?php } }}?>
		                                    </select>
	                                    </div>
	                                </div>

	                                 <div class="control-group">
	                                    <label class="control-label">Interna:</label>
	                                    <div class="controls">
	                                        <label class="checkbox inline"><input type="checkbox" id="interna" name="interna" value="1" <?php if($interna=='1'){?> checked="checked" <?php }?> ></label>
	                                    </div>
	                                </div>	

	                                <div class="control-group">
	                                    <label class="control-label">Incluye IVA:</label>
	                                    <div class="controls">
	                                        <label class="checkbox inline"><input type="checkbox" id="iva" name="iva" value="1" <?php if($incluye_iva==1){?> checked="checked" <?php }?> ></label>
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


		                <?php }?>

                	
                
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