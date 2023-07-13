<?php
$pagTitle = "Catálogo de Productos - Printec";

$listaJS[] = "/js/plugins/tables/jquery.dataTables.min.js";

$urlBreadCrumbs = "<li><a href=\"/productos/\">Productos</a></li>";

require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h5>Listado de Productos</h5>				    	
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

                    if(isset($_GET['txtFiltro'])&&!empty($_GET['txtFiltro'])){

                        $buscar=$_GET['txtFiltro'];

                        //https://www.doblevela.com/images/large/A2736_lrg.jpg
                        //https://www.doblevela.com/images/medium/A2736_med.png

                        //CONCAT('http://www.doblevela.com/images/',Modelo,'.png') AS Imagen,

                        $ssql = "SELECT
                            NombreCorto,
                            Modelo,
                            Familia,
                            CONCAT('https://www.doblevela.com/images/medium/',Modelo,'_med.png') AS Imagen,
                            CONCAT('Pag. ',PaginaCatalogo,' de ',Catálogo) AS Referencia,
                            'N' as Tipo
                            FROM proddoblevela 
                            WHERE Activo='Activo'
                            AND (
                            modelo LIKE '%".$buscar."%'
                            OR nombrecorto LIKE '%".$buscar."%'
                            OR descripciondelartículo LIKE '%".$buscar."%'
                            )
                            GROUP BY NombreCorto,Modelo,Familia,PaginaCatalogo,Catálogo
                            UNION
                            SELECT
                            descrip AS NombreCorto,
                            sku AS Modelo,
                            'Especial' AS Familia,
                            CASE WHEN IFNULL(Imagen,'')=''
                            THEN 'https://puntodeventa.printec.mx/img/servicio-especial/servicio-especial.jpg'
                            ELSE 
                            CONCAT('https://puntodeventa.printec.mx/img/servicio-especial/',Imagen)
                            END
                            AS Imagen,
                            '' AS Referencia,
                            'E' AS Tipo
                            FROM cproducto
                            WHERE sta=1
                            AND (
                            descrip LIKE '%".$buscar."%'
                            OR sku LIKE '%".$buscar."%'
                            )
                            AND tipo='E'
                            AND csucursal_folio=".$_SESSION['rtSucursal']."
                             ";
                        
                    }else{

                         $ssql = "SELECT
                            NombreCorto,
                            Modelo,
                            Familia,
                            CONCAT('http://www.doblevela.com/images/',Modelo,'.png') AS Imagen,
                            CONCAT('Pag. ',PaginaCatalogo,' de ',Catálogo) AS Referencia,
                            'N' as Tipo
                            FROM proddoblevela 
                            WHERE Activo='Activo'
                            AND masvendidos=1
                            GROUP BY NombreCorto,Modelo,Familia,PaginaCatalogo,Catálogo
                            Limit 8 ";

                    }

                    $resultado = $link->query($ssql);
                ?> 


                <?php if($txtFolio>0){ ?>

                <div class="span12">
                    
                        <!-- Buttons -->
                        <div class="widget">
                            <div class="navbar"><div class="navbar-inner"><h6>Cotización Actual</h6></div></div>
                            <div class="well body"> 
                                <a class="btn btn-large btn-block btn-primary" href="/cotizaciones/edit_cotizacion.php?id=<?=$txtFolio?>&evt=U">Ir a la cotización actual</a>
                            </div>
                        </div>
                        <!-- /buttons -->
                        
                    </div>


                <?php } ?>




                <?php

                    if(isset($_GET['txtFiltro'])&&!empty($_GET['txtFiltro'])){?>

                <h5 class="widget-name"><i class="icon-th"></i>Productos que coinciden con "<?=$buscar?>"</h5>

            <?php }else{?>

                <h5 class="widget-name"><i class="icon-th"></i>Productos mas vendidos</h5>

            <?php }?>


                <div class="media row-fluid">

                    <?php 
                                if($resultado->num_rows>0){

                                    $resultado->data_seek(0);                           
                                    while($dt=$resultado->fetch_assoc()){
                                ?>
                                <div style="width:250px;display: inline-block;margin-right: 10px">
                    <div class="widget">
                            <div class="well">
                                <div class="view" style="text-align: center;">                                   
                                    <a href="/productos/detalle.php?modelo=<?=$dt['Modelo']?>&id=<?=$txtFolio?>">
                                    

                                        <?php if($dt['Tipo']=='N'){
                                            $fotoarticulo = "https://www.doblevela.com/images/".str_replace(" ","",$dt['Modelo']).".png";

                                            if (!file_exists($fotoarticulo))
                                                  $fotoarticulo = str_replace(".png",".jpg",$fotoarticulo);

                                            if (!file_exists($fotoarticulo))
                                                  $fotoarticulo = "https://www.doblevela.com/images/large/".str_replace(" ","",$dt['Modelo'])."_lrg.jpg";
                                            
                                        ?>
                                        <img src="<?=$fotoarticulo?>" width="120" height="120">
                                        <?php }else{ ?>
                                            <img src="<?=$dt['Imagen']?>" width="120" height="120">
                                        <?php }?>



                                    </a>
                                </div>
                                <div class="item-info">
                                    <a href="/productos/detalle.php?modelo=<?=$dt['Modelo']?>&id=<?=$txtFolio?>" title="" class="item-title"><?=$dt['Modelo']?></a>
                                    <p><?=$dt['NombreCorto']?></p>
                                    <p style="font-size: 10px;text-align: right;">
                                        <?=$dt['Familia']?>
                                    </p>
                                </div>
                            </div>
                        </div> 
                    </div>

                        <?php } } ?>                                   

                   
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