<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
restrinct();

$ln = ln();

// load library
require $_SERVER['DOCUMENT_ROOT'].'/inc/php-excel.class.php';

$ssql = "SELECT
        a.folio,
        a.nombre,
        a.apellido,
        a.email,
        a.telefono,
        a.cmembresia_folio,
        a.ccanal_folio,
        b.descrip AS membresia,
        c.descrip AS canal
        FROM ccliente a
        INNER JOIN cmembresia b ON a.cmembresia_folio=b.folio
        INNER JOIN ccanal c ON a.ccanal_folio=c.folio
        WHERE a.sta=1
        AND a.csucursal_folio=".$_SESSION['rtSucursal']."
        AND b.sta=1
        AND c.sta=1
        ORDER BY a.folio DESC ";
$resultado = $link->query($ssql);

//$datalista = array();

$data = array(
        1 => array ('Folio', 'Nombre','Apellido','Email','Telefono','Canal')        
        );

if($resultado->num_rows>0){

$resultado->data_seek(0);                           
while($dt=$resultado->fetch_assoc()){    

        $data[]  = array($dt['folio'],$dt['nombre'],$dt['apellido'],$dt['email'],$dt['telefono'],$dt['canal']);
    }
}

// generate file (constructor parameters are optional)
$xls = new Excel_XML('UTF-8', false, 'Listado de Clientes');
$xls->addArray($data);
$xls->generateXML('Listado-de-Clientes');

?>