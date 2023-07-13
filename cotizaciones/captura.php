<?php
	$accion = 'Crear Cotizacion';
	$id=0;

	if($_GET){
		if($_GET['evt']=='I'&&isset($_GET['id'])){
			$id=intval($_GET['id']);

			if($id>0)
			$accion = 'Editar Cotizacion';
		}
	}

	$pagTitle = 'Printec - '.$accion;

	$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

	$urlBreadCrumbs = "<li><a href=\"/cotizaciones/\">Cotizaciones</a></li>";
	$urlBreadCrumbs .= "<li><a href=\"/cotizaciones/captura.php?evt=I&id=0\">Crear cotizacion</a></li>";

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

                		<!-- Horizontal form -->
	                    <form id="frmProveedorTour" action="insertar.php" class="form-horizontal" method="post">
	                        <div class="widget">
	                            <ul class="toolbar">
			                        <li><a href="/clientes/captura.php?evt=I&id=0" title=""><i class="icon-user"></i><span>Crear Cliente</span></a></li>
			                        <li><a href="/cotizaciones/" target="blank" title=""><i class="ico-search"></i><span>Cot. Aprobadas</span></a></li>
			                        <li><a href="/cotizaciones/pendientes.php" target="blank" title=""><i class="ico-search"></i><span>Cot. Pendientes</span></a></li>
			                    </ul>
	                            <input type="hidden" id="txtModulo" name="txtModulo" value="cotizaciones" />
	                            <input type="hidden" id="txtFolio" name="txtFolio" value="<?=$id?>" />
	                            <input type="hidden" id="cproducto_folio" name="cproducto_folio" value="0" />

	                            <div class="well">

	                            	<?php if($id>0){?>
	                            	<div class="control-group">
	                                    <label class="control-label">Cotizacion </label>
	                                    <div class="controls"><strong>PRINT<?=$id?></strong></div>
	                                </div>
	                                <input type="hidden" id="ccliente_folio" name="ccliente_folio" value="0" />
	                                <?php }else {?>

	                                <div class="control-group">
	                                    <label class="control-label">Cliente <span class="text-error">*</span></label>
	                                    <div class="controls">
	                                        <select id="ccliente_folio" name="ccliente_folio" class="validate[required] styled" >
		                                        <option value="0">NA</option>

		                                        <?php
				                                	//Obtener aeropuertos
									    			$ssql = "SELECT
															a.folio,
															CASE WHEN IFNULL(d.descrip,'') = '' THEN CONCAT('[Sin Corporativo]',' ',a.nombre,' ',a.apellido) ELSE CONCAT('[',d.descrip,'] ',a.nombre,' ',a.apellido)  END AS nombre
															FROM ccliente a
															INNER JOIN cmembresia b ON a.cmembresia_folio=b.folio
															INNER JOIN ccanal c ON a.ccanal_folio=c.folio
															LEFT JOIN ccorporativo d ON a.ccorporativo_folio=d.folio AND d.sta=1
															WHERE a.sta=1
															AND b.sta=1
															AND c.sta=1                                
															ORDER BY d.descrip,a.nombre,a.apellido ASC   ";
									    			$rsA = $link->query($ssql);

									    			if($rsA->num_rows>0){

														$rsA->data_seek(0);                          	
														while($dtA=$rsA->fetch_assoc()){

									    			if($dtA['folio']==$cmembresia_folio){						    			
				                                ?>
		                                        <option value="<?=$dtA['folio']?>" selected="selected"><?=$dtA['nombre']?></option>
		                                        <?php }else{?>
		                                        <option value="<?=$dtA['folio']?>"><?=$dtA['nombre']?></option>	
		                                        <?php } }}?>
		                                    </select>
	                                    </div>
	                                </div>


	                                <?php }?>	                                

	                            	<div class="control-group">
	                                    <label class="control-label">Producto <span class="text-error">*</span></label>
	                                    <div class="controls" style="width: 450px">
	                                    	<?php
				                                	//Obtener aeropuertos
									    			$ssql = "SELECT
							                                folio AS Folio,
							                                descrip AS Descripcion,
							                                precio AS Precio,
							                                promo AS Promo,
							                                moneda AS Moneda,
							                                sku AS Sku,
							                                CASE tipo
							                                WHEN 'N' THEN 'Normal'
							                                ELSE 'Especial'
							                                END AS Tipo
							                                FROM cproducto
							                                WHERE sta=1
							                                ORDER BY descrip ASC  ";
									    			$rsA = $link->query($ssql);						    			
				                                ?>                                        

		                                    <table class="table table-striped table-bordered" id="data-table2">
				                            <thead>
				                                <tr>
				                                	<th>Acción</th>  
				                                    <th>Descripción</th>
				                                </tr>
				                            </thead>
				                            <tbody>
				                            	<?php 
				                            	if($rsA->num_rows>0){

													$rsA->data_seek(0);                          	
													while($dt=$rsA->fetch_assoc()){
				                            	?>
				                                <tr id="reg-<?=$dt['Folio']?>">
				                                	<td>
				                                		<ul class="table-controls">                                            
				                                            <li><a href="#" class="btn tip accionGridSel">Seleccionar</a> </li>
				                                        </ul>
				                                	</td>
				                                    <td><?=$dt['Descripcion']?> [<?=$dt['Sku']?>]</td>
				                                </tr>
				                                <?php } }else{?>
				                                <tr><td colspan="2">No existen Productos</td></tr>
				                                <?php }?>
				                            </tbody>
				                        </table>

	                                    </div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Concepto <span class="text-error">*</span></label>
	                                    <div class="controls"><input style="width:250px" type="text" class="span12" id="concepto" name="concepto" value="<?=$concepto?>" placeholder="" maxlength="30" /></div>
	                                </div>
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Precio <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="precio" name="precio" value="<?=$precio?>" placeholder="" maxlength="30" /></div>
	                                </div>

	                                <div class="control-group">
	                                    <label class="control-label">Cantidad <span class="text-error">*</span></label>
	                                    <div class="controls"><input type="text" class="span12" id="cantidad" name="cantidad" value="<?=$cantidad?>" placeholder="" maxlength="30" /></div>
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