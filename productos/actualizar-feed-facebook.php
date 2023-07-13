<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
restrinct();

$ln = ln();

// load library
require $_SERVER['DOCUMENT_ROOT'].'/inc/php-excel.class.php';


$ssql = "SELECT
'id' AS id,
'title' AS title,
'description' AS description,
'availability' AS availability,
'condition' AS 'condition',
'price' AS price,
'link' AS link,
'image_link' AS image_link,
'brand' AS brand,
'additional_image_link' AS additional_image_link,
'age_group' AS age_group,
'color' AS color,
'unisex' AS gender,
'item_group_id' AS item_group_id,
'google_product_category' AS google_product_category,
'material' AS material,
'inventory' AS inventory,
'pattern' AS pattern,
'product_type' AS product_type,
'sale_price' AS sale_price,
'sale_price_effective_date' AS sale_price_effective_date,
'shipping' AS shipping,
'shipping_weight' AS shipping_weight,
'size' AS size,
'custom_label_0' AS custom_label_0,
'custom_label_1' AS custom_label_1,
'custom_label_2' AS custom_label_2,
'custom_label_3' AS custom_label_3,
'custom_label_4' AS custom_label_4
UNION
SELECT 
p.folio AS id,
SUBSTR(LOWER(p.descrip),1,100) AS title,
IFNULL(SUBSTR(LOWER(REPLACE(v.Descrpciondelproducto,',',' ')),1,2500),CONCAT(SUBSTR(LOWER(p.descrip),1,100),'. Modelo ',v.Modelo)) AS description,
'in stock' AS availability,
'new' AS conditions,
CONCAT(IFNULL(ROUND((((p.precio + (p.precio * (SELECT IFNULL((mem.descuento / 100),0) 
FROM (csucursal suc JOIN cmembresia mem ON((suc.cmembresia_folio = mem.folio))) WHERE ((suc.sta = 1) AND (mem.sta = 1) 
AND (suc.keyword = 'printec'))))) * 1) * 1),2),0),' ',p.moneda) AS price,
(CASE WHEN (IFNULL(wp.url_producto,'') <> '') THEN CONCAT('https://printec.mx/product/',wp.url_producto,'/') ELSE (CASE WHEN (IFNULL(wp.url_categoria,'') <> '') THEN CONCAT('https://printec.mx/product-category/',wp.url_categoria,'/') ELSE 'https://printec.mx/' END) END) AS link,
CONCAT('https://www.doblevela.com/images/large/',REPLACE(v.Modelo,' ',''),'_lrg.jpg') AS image_link,
CONCAT(v.Modelo,' ',p.sku) AS brand,
CONCAT('https://www.doblevela.com/images/large/',REPLACE(v.Modelo,' ',''),'_lrg.jpg') AS additional_image_link,
'' AS age_group,
GROUP_CONCAT(LOWER(SUBSTR(p.color,6))) AS color,
'unisex' AS gender,
'' AS item_group_id,
'Furniture > Office Furniture Accessories > Desk Parts & Accessories' AS google_product_category,
v.Material AS material,
p.existencia AS inventory,
v.NombreCorto AS pattern,
v.Subfamilia AS product_type,
CONCAT(IFNULL(ROUND((((p.precio + (p.precio * (SELECT IFNULL((mem.descuento / 100),0) 
FROM (csucursal suc JOIN cmembresia mem ON((suc.cmembresia_folio = mem.folio))) WHERE ((suc.sta = 1) AND (mem.sta = 1) 
AND (suc.keyword = 'printec'))))) * 1) * 1),2),0),' ',p.moneda) AS sale_price,
'2019-10-12T0:00-23:59/2019-12-31T0:00-23:59' AS sale_price_effective_date,
'' AS shipping,
'' AS shipping_weight,
'' AS size,CONCAT('Medida: ',
v.MedidaProducto) AS custom_label_0,
CONCAT('Unidad x Empaque: ',v.UnidadporEmpaque) AS custom_label_1,
CONCAT('Características caja master medida: ',v.MedidadeCajaMaster) AS custom_label_2,
CONCAT('Características caja master peso: ',v.Peso) AS custom_label_3,
'' AS custom_label_4 
FROM ((cproducto p JOIN proddoblevela v ON((p.sku = v.Numerodearticulo))) 
LEFT JOIN productos_wp wp ON (((v.Modelo = wp.sku) AND (wp.url_categoria <> '') 
AND (wp.sku <> '')))) WHERE ((p.sta = 1) AND (v.Activo = 'Activo') AND (p.color <> 'BAJA') 
AND (p.color <> 'ACTUAL') AND (p.color <> 'NUEVO') 

AND (p.precio > 0) 
AND (IFNULL(p.descrip,'') <> ''))
AND p.tipo='N'
GROUP BY v.modelo";
$resultado = $link->query($ssql);

//AND (p.existencia > 0) 

$data = array(
        1 => array ('id', 'title','description','availability','condition','price','link','image_link','brand','additional_image_link','age_group','color','gender','item_group_id','google_product_category','material','inventory','pattern','product_type','sale_price','sale_price_effective_date','shipping','shipping_weight','size','custom_label_0','custom_label_1','custom_label_2','custom_label_3','custom_label_4')
        );

     

if($resultado->num_rows>0){

$resultado->data_seek(0);                           
while($dt=$resultado->fetch_assoc()){  

        
        //$data[]  = array($dt['SKU'],$dt['MinPrice'],$dt['MaxPrice'],$dt['StockQuantity']);  
        
        $data[]  = array($dt['id'], $dt['title'],$dt['description'],$dt['availability'],$dt['condition'],$dt['price'],$dt['link'],$dt['image_link'],$dt['brand'],$dt['additional_image_link'],$dt['age_group'],$dt['color'],$dt['gender'],$dt['item_group_id'],$dt['google_product_category'],$dt['material'],$dt['inventory'],$dt['pattern'],$dt['product_type'],$dt['sale_price'],$dt['sale_price_effective_date'],$dt['shipping'],$dt['shipping_weight'],$dt['size'],$dt['custom_label_0'],$dt['custom_label_1'],$dt['custom_label_2'],$dt['custom_label_3'],$dt['custom_label_4']);  


    }
}

// generate file (constructor parameters are optional)
$xls = new Excel_XML('UTF-8', false, 'Feed Facebook');
$xls->addArray($data);
$xls->generateXML('Feed Facebook');

?>