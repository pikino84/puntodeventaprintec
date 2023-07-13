<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
restrinct();

$ln = ln();

// load library
require $_SERVER['DOCUMENT_ROOT'].'/inc/php-excel.class.php';


$ssql = "SELECT
v.Modelo AS SKU,
ROUND((((p.precio + (p.precio * (SELECT IFNULL((mem.descuento / 100),0) 
FROM (csucursal suc JOIN cmembresia mem ON((suc.cmembresia_folio = mem.folio))) 
WHERE ((suc.sta = 1) AND (mem.sta = 1) AND (suc.keyword = 'printec'))))) * 1) * 1.16),2)
AS MinPrice,
ROUND((((p.precio + (p.precio * (SELECT IFNULL((mem.descuento / 100),0) 
FROM (csucursal suc JOIN cmembresia mem ON((suc.cmembresia_folio = mem.folio))) 
WHERE ((suc.sta = 1) AND (mem.sta = 1) AND (suc.keyword = 'printec'))))) * 1.3) * 1.16),2)
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
AND (p.precio > 0) AND (IFNULL(p.descrip,'') <> ''))
AND p.tipo='N'
AND v.Modelo not in (SELECT sku FROM wp_wc_product_meta_lookup)
GROUP BY v.modelo  ";
$resultado = $link->query($ssql);

//AND p.Modelo LIKE '%1108%'

//$datalista = array();

$data = array(
        1 => array ('Sku', 'MinPrice','MaxPrice','OnSale','StockQuantity','StockStatus','TaxStatus',
                    'MedidaProducto','UnidadporEmpaque','Material','Title','DescripCorta','DescripLarga','Color',
                    'Peso','Dimensiones','CategoriaWP','Imagen','Imagen1','Imagen2','Imagen3','Imagen4','Imagen5')
        );

       

if($resultado->num_rows>0){

$resultado->data_seek(0);                           
while($dt=$resultado->fetch_assoc()){

        $colores = array();

        $colores[1]='';
        $colores[2]='';
        $colores[3]='';
        $colores[4]='';
        $colores[5]='';

        //$color = str_replace(',', ' ', $dt['Color']);
        $lista = explode(',',$dt['Color']);
        $i=1;
        foreach ($lista as $li) {

            if($li=='') continue;

            $colores[$i] = "https://www.doblevela.com/images/large/".$dt['SKU']."_".str_replace(' ','',strtolower($li))."_lrg.jpg";
            $i++;
            
        }

        //print_r($colores);
        //exit();

        $data[]  = array($dt['SKU'],$dt['MinPrice'],$dt['MaxPrice'],$dt['OnSale'],$dt['StockQuantity'],$dt['StockStatus'],$dt['TaxStatus'],
                         $dt['MedidaProducto'],$dt['UnidadporEmpaque'],$dt['Material'],$dt['Title'],$dt['DescripCorta'],$dt['DescripLarga'],$dt['Color'],
                         $dt['Peso'],$dt['Dimensiones'],$dt['CategoriaWP'],$dt['Imagen'],$colores[1],$colores[2],$colores[3],
                         $colores[4],$colores[5]
                        );
    }
}

// generate file (constructor parameters are optional)
$xls = new Excel_XML('UTF-8', false, 'Listado de Productos');
$xls->addArray($data);
$xls->generateXML('Listado-de-Productos');

?>