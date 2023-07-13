<?php
	$accion = 'Crear Tarifa';
	$id=0;

	if($_GET){
		if($_GET['evt']=='U'&&isset($_GET['id'])){
			$id=intval($_GET['id']);
		}
	}

	$pagTitle = 'Printec - '.$accion;

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$urlBreadCrumbs = "<li><a href=\"/tarifasporrango/\">Tarifas por rango</a></li>";
	$urlBreadCrumbs .= "<li><a href=\"/tarifasporrango/captura.php?evt=I&id=0\">Crear Tarifa</a></li>";

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

			    			$cproducto_folio = 0;	
							$crango_folio = 0;	
							$precio = 0;
							$precio_porunidad=0;	

			    			if($id>0){

			    				$ssql = "SELECT
                                r.folio,
                                CASE p.tipo
                                WHEN 'I' THEN 'Tipo Impresión'
                                ELSE 'Numero tinta'
                                END AS tipo,
                                p.sku AS sku,
                                p.descrip AS nombre,
                                c.rango_inicial AS inicial,
                                c.rango_final AS final,
                                r.precio AS precio,
                                r.cproducto_folio as cproducto_folio,
                                r.crango_folio as crango_folio
                                FROM tblproductorango r
                                INNER JOIN cproducto p ON r.cproducto_folio=p.folio
                                INNER JOIN crango c ON r.crango_folio=c.folio
                                WHERE r.sta=1
                                AND p.sta=1
                                AND c.sta=1
										AND r.folio='$id'";
								$rs = mysql_query($ssql,$link);

								if(mysql_num_rows($rs)>0){
									$dt = mysql_fetch_assoc($rs);

					    			
					    			$cproducto_folio =$dt['cproducto_folio'];
									$crango_folio = $dt['crango_folio'];	
									$precio =$dt['precio'];	

								}

			    			}

			    		?>
                 

                		<!-- Horizontal form -->
	                    <form id="frmProveedorTour" action="insertar.php" class="form-horizontal" method="post">
	                        <div class="widget">
	                            <div class="navbar"><div class="navbar-inner"><h6>Información de la Tarifa</h6></div></div>
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="tarifasporrango" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="<?=$id?>" />

	                            <div class="well">

	                            	<div class="control-group">
	                                    <label class="control-label">Producto <span class="text-error">*</span></label>
	                                    <div class="controls">
	                                        <select id="cproducto_folio" name="cproducto_folio" class="validate[required] styled" data-prompt-position="topLeft:-1,-5">
		                                        <option value="0">NA</option>

		                                        <?php
				                                	//Obtener aeropuertos
									    			$ssql = "
															SELECT
															folio,
															descrip AS nom
															FROM cproducto
															WHERE sta=1
															AND (tipo='T' or tipo='I') 
															ORDER BY tipo,descrip ASC ";
									    			$rsA = mysql_query($ssql,$link);

									    			if(mysql_num_rows($rsA)>0){
									    			while($dtA=mysql_fetch_assoc($rsA)){

									    			if($dtA['folio']==$cproducto_folio){						    			
				                                ?>
		                                        <option value="<?=$dtA['folio']?>" selected="selected"><?=$dtA['nom']?></option>
		                                        <?php }else{?>
		                                        <option value="<?=$dtA['folio']?>"><?=$dtA['nom']?></option>	
		                                        <?php } }}?>
		                                    </select>
	                                    </div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Rango <span class="text-error">*</span></label>
	                                    <div class="controls">
	                                        <select id="crango_folio" name="crango_folio" class="validate[required] styled" data-prompt-position="topLeft:-1,-5">
		                                        <option value="0">NA</option>

		                                        <?php
				                                	//Obtener aeropuertos
									    			$ssql = "
															SELECT
															folio,
															concat('De ',rango_inicial,' A ',rango_final) AS nom
															FROM crango
															WHERE sta=1
															ORDER BY rango_inicial ASC ";
									    			$rsA = mysql_query($ssql,$link);

									    			if(mysql_num_rows($rsA)>0){
									    			while($dtA=mysql_fetch_assoc($rsA)){

									    			if($dtA['folio']==$crango_folio){						    			
				                                ?>
		                                        <option value="<?=$dtA['folio']?>" selected="selected"><?=$dtA['nom']?></option>
		                                        <?php }else{?>
		                                        <option value="<?=$dtA['folio']?>"><?=$dtA['nom']?></option>	
		                                        <?php } }}?>
		                                    </select>
	                                    </div>
	                                </div>
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Precio <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="precio" name="precio" value="<?=$precio?>" placeholder="" maxlength="10" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">¿Precio por unidad?:</label>
	                                    <div class="controls">
	                                        <label class="checkbox inline"><input type="checkbox" id="precio_porunidad" name="precio_porunidad" value="1" <?php if($precio_porunidad=='1'){?> checked="checked" <?php }?> ></label>
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