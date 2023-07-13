<?php
$pagTitle = "Detalle del producto - Printec";

$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

$urlBreadCrumbs = "<li><a href=\"/productos/\">Productos</a></li>";

require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h5>Detalle del producto</h5>				    	
    </div>			    	
</div>
<!-- /page header -->

<!-- Default datatable -->
<div class="widget">  

            <?php
            $txtFolio=0;

                    if(isset($_GET['id'])&&!empty($_GET['id'])){
                        $txtFolio = $_GET['id'];
                    }

            ?>


            <form class="search widget" action="/productos/index.php" method="get">
                <input type="hidden" name="id" id="id" value="<?=$txtFolio?>">
                    <div class="autocomplete-append">                        
                        <input type="text" placeholder="Ingresa nombre o modelo del producto " id="txtFiltro" name="txtFiltro" class="ui-autocomplete-input" autocomplete="off"><span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span>
                        <input type="submit" class="btn btn-info" value="Buscar">
                    <ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="z-index: 1; display: none;"></ul></div>
                </form>                

                <?php

                    

                    if(isset($_GET['modelo'])&&!empty($_GET['modelo'])){

                        $modelo=$_GET['modelo'];

                        $ssql = "SELECT
                        p.folio AS Folio,
                        p.descrip AS Descripcion,
                        CASE WHEN p.tipo='N'
                        THEN
                        IFNULL(p.precio+(p.precio*(SELECT
                        IFNULL(mem.descuento/100,0)
                        FROM cmembresia mem 
                        WHERE mem.sta=1
                        AND mem.folio='".$_SESSION['rtMembresia']."')),0) 
						ELSE
						p.precio
						END
                        AS Precio,
                        p.sku AS Sku,
                        p.moneda AS Moneda,
                        p.color AS Color,
                        p.existencia AS Existencia,
                        CASE WHEN IFNULL(p.Modelo,'')=''
                        THEN v.Modelo
                        ELSE 
                        p.Modelo
                        END
                        AS Modelo,
                        v.Familia AS Familia,
                        v.Subfamilia AS Subfamilia,
                        v.Material AS Meterial,
                        v.MedidaProducto AS MedidaProducto,
                        v.PaginaCatalogo AS PaginaCatalogo,
                        v.Catálogo AS Catalogo,
                        v.Descrpciondelproducto AS DescrpciondelProducto,
                        v.TipodeImpresion AS TipodeImpresion,
                        v.TécnicaImpresion1 AS TecnicaImpresion1,
                        v.PrecioDistribuidor AS PrecioDistribuidor,
                        v.PrecioPublico AS PrecioPublico,
                        v.MedidadeCajaMaster AS MedidadeCajaMaster,
                        v.Largo AS Largo,
                        v.Ancho AS Ancho,
                        v.Altura AS Altura,
                        v.Volumen AS Volumen,
                        v.Peso AS Peso,
                        v.Descripciondelartículo AS DescripciondelArticulo,
                        v.EnStock AS EnStock,
                        v.Comprometido AS Comprometido,
                        v.Disponible AS Disponible,
                        v.PORLLEGAR AS PorLlegar,
                        v.FECHAAPROXDELLEGADA AS FechaProxLlegada2,
                        v.VisibleECommerce AS VisibleECommerce,
                        p.StockInmediato,
                        p.Disponible24hrs,
                        p.Disponible48hrs,
                        p.Disponible72hrs,
                        p.Total,
                        p.Apartado,
                        p.preciolist,
                        p.PorLlegar,
                        p.FechaAproxDeLlegada,
                        v.UnidadporEmpaque AS UnidadporEmpaque,
                        v.nombrecorto as NombreCorto,
                        p.tipo as Tipo,
                        CASE WHEN IFNULL(p.Imagen,'')=''
                        THEN 'http://puntodeventa.printec.mx/img/servicio-especial/servicio-especial.jpg'
                        ELSE 
                        CONCAT('http://puntodeventa.printec.mx/img/servicio-especial/',Imagen)
                        END
                        AS Imagen,
                        p.fe_edicion as fe_edicion
                        FROM cproducto p
                        LEFT JOIN proddoblevela v ON p.sku=v.Numerodearticulo AND v.Activo='Activo'
                        WHERE p.sta=1                        
                        AND p.color!='BAJA'
                        AND p.color!='ACTUAL'
                        AND p.color!='NUEVO'
                        
                        AND (p.Modelo='".$modelo."' or v.Modelo='".$modelo."') ";
                        $resultado = $link->query($ssql);

                        //AND p.existencia>0
                        
                    }                   
                ?>  

                <div class="row-fluid">

                    <?php 
                                if($resultado->num_rows>0){
                                    $resultado->data_seek(0);
                                    $listaagregados=array();
                                    
                                    $producto = $resultado->fetch_assoc();
                                ?>
                    <div class="span5">
                        <div class="widget">
                            <div class="navbar"><div class="navbar-inner"><h6><?=$producto['NombreCorto']?></h6></div></div>
                            <div class="well body">
                                <ul class="stats-details">
                                    <li>                                        
                                        <strong>Material: <?=$producto['Meterial']?></strong><br/>
                                        <strong>Medida: <?=$producto['MedidaProducto']?></strong> <br/>                                       
                                        <strong>Unidad x Empaque: <?=$producto['UnidadporEmpaque']?> pzas.</strong><br/>
                                        <strong>Página: <?=$producto['PaginaCatalogo']." en catálogo ".$producto['Catalogo']?></strong><br/>
                                        <strong>Medida de Caja Master: <?=$producto['MedidadeCajaMaster']?></strong><br/>
                                        <strong>Peso de Caja Master: <?=$producto['Peso']?></strong>
                                    </li>
                                    <li>
                                        <div class="number">                                            
                                            <span>Modelo <?=$producto['Modelo']?></span>
                                        </div>
                                    </li>
                                </ul>
                                <div>
                                    <?php if($producto['Tipo']=='N'){

                                    //https://www.doblevela.com/images/large/A2736_lrg.jpg
                                    //https://www.doblevela.com/images/medium/A2736_med.png

                                    //http://imagenes.printec.mx/< ?=$producto['Modelo']? >_lrg.jpg
                                    ?>
                                    <img src="https://www.doblevela.com/images/large/<?=$producto['Modelo']?>_lrg.jpg" width="400" />
                                <?php }else{ ?>
                                    <img src="<?=$producto['Imagen']?>" width="400" />
                                <?php }?>
                                    <p><?=$producto['DescrpciondelProducto']?></p>
                                
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="span7">
                        <div class="widget">
                    <div class="navbar"><div class="navbar-inner"><h6>Inventario Actual (Actualizado el: <?=substr($producto['fe_edicion'],6,2).'/'.substr($producto['fe_edicion'],4,2).'/'.substr($producto['fe_edicion'],0,4)?>)</h6></div></div>

                    <div class="table-overflow">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cantidad/M2</th>
                                    <th>Clave</th>
                                    <th>Color</th>
                                    <th>Precio</th>
                                    <th>Existencia</th>                                    
                                    <th>Total</th> 
                                    <th>Apartado</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <form method="post" action="/cotizaciones/insertar.php">
                                    <input type="hidden" name="txtFolio" id="txtFolio" value="<?=$txtFolio?>">
                                <?php 
                                
                                $clase ="success";

                                //while($liproductos=mysql_fetch_assoc($rs)){
                                do{

                                    if (in_array($producto['Folio'], $listaagregados))
                                        continue;

                                    if($clase=="success") $clase="info"; else $clase="success";
                                ?>
                                <tr class="<?=$clase?>">
                                    <td>
                                        <?php if($producto['Existencia']>0) { $disponibles=true; ?>
                                         <?php if($producto['Tipo']=='N'){?>
                                            <input type="number" name="txtCantidad_<?=$producto['Folio']?>" placeholder="0" min="1" max="<?=$producto['Existencia']?>" >
                                         <?php }else{?>
                                            <input type="text" name="txtCantidad_<?=$producto['Folio']?>" placeholder="0" >
                                         <?php } ?>
                                    <?php }else{?>
                                        <strong class="orange">Comunícate con tu asesor</strong>
                                    <?php }?>
                                    </td>
                                    <td>
                                        <?=$producto['Sku']?>
                                    </td>
                                    <td>
                                        <?=substr($producto['Color'],4)?>
                                    </td>
                                    <td>
                                        <?=round($producto['Precio'],2)?>
                                    </td>
                                    <td>
                                        <?=$producto['Existencia']?>
                                    </td>                                   
                                    <td>
                                        <?=$producto['Total']?>
                                    </td>
                                    <td>
                                        <?=$producto['Apartado']?>
                                    </td>                                    
                                </tr>   
                                <?php 
                                        $listaagregados[]=$producto['Folio'];
                                    }while($producto=$resultado->fetch_assoc()); ?>
                                <?php if ($disponibles){ ?>
                                 <tr>                                        
                                        <td><input class="btn btn-blue" type="submit" name="btnCotizar" value="Agregar"></td>
                                        <td colspan="6">* Precios más iva</td>
                                    </tr>
                                <?php } ?>
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
                    </div>
                    

                        <?php  }else{ ?>

                             <h5 class="widget-name"><i class="ico-remove-circle"></i>Producto no enconrtrado</h5>

                        <?php }?>                                   

                   
                </div>


    
</div>
<!-- /default datatable -->

</div>
<!-- /content wrapper -->

</div>
<!-- /content -->

</div>
<!-- /content container -->


<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php');
?>