<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/backend/inc/configuracion.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/backend/inc/funciones.php');

$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
restrinct();

$ln = ln();

// load library
require $_SERVER['DOCUMENT_ROOT'].'/backend/inc/php-excel.class.php';

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
        AND tipo='N'
        ORDER BY descrip ASC  ";
$resultado = $link->query($ssql);

//$datalista = array();

$data = array(
        1 => array ('Folio', 'Descripcion','Precio Regular','Precio Venta','Sku','Moneda','Tipo')        
        );

if($resultado->num_rows>0){

$resultado->data_seek(0);                           
while($dt=$resultado->fetch_assoc()){

        $data[]  = array($dt['Folio'],$dt['Descripcion'],$dt['Precio'],$dt['Promo'],$dt['Sku'],$dt['Moneda'],$dt['Tipo']);
    }
}

// generate file (constructor parameters are optional)
$xls = new Excel_XML('UTF-8', false, 'Listado de Productos');
$xls->addArray($data);
$xls->generateXML('Listado-de-Productos');

?>