<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
restrinct();

$ln = ln();

// load library
require $_SERVER['DOCUMENT_ROOT'].'/inc/php-excel.class.php';

//1.16


$ssql = "SELECT
v.Modelo AS SKU,
ROUND((((p.precio + (p.precio * (SELECT IFNULL((mem.descuento / 100),0) 
FROM (csucursal suc JOIN cmembresia mem ON((suc.cmembresia_folio = mem.folio))) 
WHERE ((suc.sta = 1) AND (mem.sta = 1) AND (suc.keyword = 'printec'))))) * 1) * 1),2)
AS MinPrice,
ROUND((((p.precio + (p.precio * (SELECT IFNULL((mem.descuento / 100),0) 
FROM (csucursal suc JOIN cmembresia mem ON((suc.cmembresia_folio = mem.folio))) 
WHERE ((suc.sta = 1) AND (mem.sta = 1) AND (suc.keyword = 'printec'))))) * 1.3) * 1),2)
AS MaxPrice,
1 AS OnSale,
sum(p.existencia) AS StockQuantity,
'instock' AS StockStatus,
'taxable' AS TaxStatus,
v.MedidaProducto AS MedidaProducto,
v.UnidadporEmpaque AS UnidadporEmpaque,
v.Material AS Material,
v.NombreCorto AS Title,
p.descrip AS DescripCorta,
IFNULL(SUBSTR(LOWER(REPLACE(v.Descrpciondelproducto,',',' ')),1,2500),CONCAT(SUBSTR(LOWER(p.descrip),1,100),'. Modelo ',v.Modelo)) AS DescripLarga,
GROUP_CONCAT(LOWER(SUBSTR(p.color,6))) AS Color,
v.Peso AS Peso,
v.MedidadeCajaMaster AS Dimensiones,
v.categoria_wp AS CategoriaWP,
CONCAT('https://www.doblevela.com/images/large/',REPLACE(v.Modelo,' ',''),'_lrg.jpg') AS Imagen
FROM ((cproducto p JOIN proddoblevela v ON((p.sku = v.Numerodearticulo)))
LEFT JOIN productos_wp wp ON(((v.Modelo = wp.sku) 
AND (wp.url_categoria <> '') AND (wp.sku <> '')))) 
WHERE ((p.sta = 1) AND (v.Activo = 'Activo') AND (p.color <> 'BAJA') 
AND (p.color <> 'ACTUAL') AND (p.color <> 'NUEVO') 

AND (p.precio > 0) 
AND (IFNULL(p.descrip,'') <> ''))
AND p.tipo='N'
GROUP BY v.modelo  ";
$resultado = $link->query($ssql);

//AND (p.existencia > 0) 

$data = array(
        1 => array ('SKU', 'MinPrice','MaxPrice','StockQuantity')
        );

     

if($resultado->num_rows>0){

$resultado->data_seek(0);                           
while($dt=$resultado->fetch_assoc()){  

        
        $data[]  = array($dt['SKU'],$dt['MinPrice'],$dt['MaxPrice'],$dt['StockQuantity']);  
        
    }
}

// generate file (constructor parameters are optional)
$xls = new Excel_XML('UTF-8', false, 'Listado de Productos actualizar precios');
$xls->addArray($data);
$xls->generateXML('Listado de Productos actualizar precios');

?>