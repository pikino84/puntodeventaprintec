<?php
error_reporting(1);
ini_set('display_errors', 1);

$bd_host = "localhost";
$bd_usuario = "u185031927_prinusr";
$bd_pwd = "TsKDBh#Q0?i";
$bd_nombre = "u185031927_printecdb";

//$link = mysql_connect($bd_host, $bd_usuario, $bd_pwd);
//mysql_select_db($bd_nombre, $link);
//mysql_query("SET NAMES 'utf8'");

$mysqli = new mysqli('localhost', $bd_usuario, $bd_pwd, $bd_nombre);

if ($mysqli->connect_error) {
    die('Error de Conexión (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}


$resultadoR = $mysqli->query("select folio,sku from cproducto where sta='1'  ");

if($resultadoR->num_rows>0){

    while ($rowR = $resultadoR->fetch_assoc()) {



$ws = new SoapClient('http://srv-datos.dyndns.info/doblevela/service.asmx?WSDL');

$paramWs =array("Key"=>'5Dp/qvDrxtUQypRLJXJRPQ==','codigo'=>'2110');

print_r($paramWs);
$resul= $ws->GetExistencia($paramWs);
$Data =$resul->GetExistenciaResult;


//print_r($JsonResul);
//exit();

//$paramWs =array("Key"=>'iknKDpgjsPUmXlIvX2lkeQ==','Codigo'=>'ASP2020.02');
//$resul= $ws->GetProduct($paramWs);

//print_r($resul);
//$Data =$resul->GetProductResult;


print_r($Data);
exit();

$JsonResul = json_decode($Data,true);

//echo "Total = ".count($JsonResul['Resultado']);
//exit();

$fe_alta=date("Ymd");

//print_r($JsonResul);
//exit();

if ($JsonResul['intCodigo']>0) {
	echo "Error en el request";
	exit();
}

//print_r($JsonResul['Resultado']);
//exit();

foreach ($JsonResul['Resultado'] as $liprod) { //print_r($liprod);exit();

    
    $clave=(string)$liprod['CLAVE'];
    $descrip=(string)$liprod['NOMBRE'];
    $precio=(float)$liprod['Price'];
    $sta=1;
    $color=(string)$liprod['COLOR'];
    $existencia=(int)$liprod['EXISTENCIAS'];    
    $StockInmediato= 0; //(int)$liprod->StockInmediato;
    $Disponible24hrs=0; //(int)$liprod->Disponible24hrs;
    $Disponible48hrs=0; //(int)$liprod->Disponible48hrs;
    $Disponible72hrs=0; //(int)$liprod->Disponible72hrs;
    $Total=(int)$liprod['EXISTENCIAS']; //(int)$liprod->Total;
    $Apartado=(int)$liprod['Apartado'];
    $preciolist=(float)$liprod['PriceList'];
    $PorLlegar=0; //(int)$liprod->POR_LLEGAR;
    $FechaAproxDeLlegada=""; //(string)$liprod->FECHA_APROX_DE_LLEGADA;
    $estatus='ACTIVO'; //(string)$liprod->ESTATUSPRODUCTO;

    $modelo=(string)$liprod['MODELO'];
    $nombrecorto=(string)$liprod['Nombre Corto'];
    $unidadempaque=(string)$liprod['Unidad Empaque'];
    $medidacajamaster=(string)$liprod['Medida Caja Master'];
    $pesocaja=(string)$liprod['Peso caja'];
    $descripcion=(string)$liprod['Descripcion'];

    if($estatus=='ACTIVO'){

    //echo $ssql="select folio from cproducto where sku='".$clave."' and color='".$color."'";
    //$rs = mysql_query($ssql,$link);

    $resultado = $mysqli->query("select folio from cproducto where sku='".$clave."' and color='".$color."' and sta='1'  ");

    //print_r($resultado);
    //exit();




    if($resultado->num_rows>0){

            $ssql="update cproducto set
                    descrip='".$descrip."',
                    precio=$precio,
                    sta=$sta,
                    fe_edicion='$fe_alta',
                    color='".$color."',
                    existencia=$existencia,            
                    StockInmediato=$StockInmediato,
                    Disponible24hrs=$Disponible24hrs,
                    Disponible48hrs=$Disponible48hrs,
                    Disponible72hrs=$Disponible72hrs,
                    Total=$Total,
                    Apartado=$Apartado,
                    preciolist=$preciolist,
                    PorLlegar=$PorLlegar,
                    FechaAproxDeLlegada='".$FechaAproxDeLlegada."'
                    where sku='".$clave."'
                    and color='".$color."'
                    and sta='1' ";
            //mysql_query($ssql,$link);
            $mysqli->query($ssql);
            //exit();
            //echo $ssql.";<br/>";


    }else{

        $ssql="INSERT INTO cproducto
                            (
                            descrip,
                            precio,
                            sku,
                            tipo,
                            sta,
                            fe_alta,
                            hr_alta,
                            usr_alta,
                            moneda,
                            color,
                            existencia,
                            csucursal_folio,
                            StockInmediato,
                            Disponible24hrs,
                            Disponible48hrs,
                            Disponible72hrs,
                            Total,
                            Apartado,
                            preciolist,
                            PorLlegar,
                            FechaAproxDeLlegada
                            )
                            values
                            (
                            '$descrip',
                            '$precio',
                            '$clave',
                            'N',
                            '1',
                            '$fe_alta',
                            '17:00',
                            'admin',
                            'MXN',
                            '$color',
                            '$existencia',
                            '0',
                            '$StockInmediato',
                            '$Disponible24hrs',
                            '$Disponible48hrs',
                            '$Disponible72hrs',
                            '$Total',
                            '$Apartado',
                            '$preciolist',
                            '$PorLlegar',
                            '$FechaAproxDeLlegada'
                            )";
            //mysql_query($ssql,$link);
            $mysqli->query($ssql);

            //echo "No entro";
            //exit();
            


    }

/*
    $ssql="SELECT folio FROM proddoblevela  WHERE numerodearticulo='$clave' AND color='$color' ";
    $rs = mysql_query($ssql,$link);

    if(mysql_num_rows($rs)==0){    */

    $resultado = $mysqli->query("SELECT folio FROM proddoblevela  WHERE numerodearticulo='$clave' AND color='$color' AND Activo='Activo' ");

    if($resultado->num_rows==0){       

        $ssql="insert into proddoblevela
                    (
                    Sku,
                    NombreCorto,
                    Modelo,
                    Color,
                    Numerodearticulo,
                    UnidadporEmpaque,
                    FECHADECREACION,
                    Familia,
                    Subfamilia,
                    Material,
                    MedidaProducto,
                    PaginaCatalogo,
                    Catálogo,
                    Descrpciondelproducto,
                    TipodeImpresion,
                    TécnicaImpresion1,
                    TécnicaImpresion2,
                    TécnicaImpresion3,
                    TécnicaImpresion4,
                    PrecioDistribuidor,
                    PrecioPublico,
                    Largo,
                    Ancho,
                    Altura,
                    MedidadeCajaMaster,
                    Volumen,
                    Peso,
                    Descripciondelartículo,
                    Activo,
                    InformaciondeOferta,
                    Promocion,
                    Observaciones,
                    Tintas,
                    EnStock,
                    Comprometido,
                    Disponible,
                    PORLLEGAR,
                    FECHAAPROXDELLEGADA,
                    VisibleECommerce,
                    masvendidos,
                    fe_alta)
                    values
                    (
                    '0',
                    '$nombrecorto',
                    '$modelo',
                    '$color',
                    '$clave',
                    '$unidadempaque',
                    '',
                    '',
                    '',
                    '',
                    '$medidacajamaster',
                    '',
                    '',
                    '$descripcion',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '0',
                    '0',
                    '',
                    '',
                    '',
                    '$medidacajamaster',
                    '',
                    '$pesocaja',
                    '$descrip',
                    '$estatus',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '0',
                    '$fe_alta'
                    )";
           // mysql_query($ssql,$link);
           $mysqli->query($ssql);


    }else{          


        $ssql="update proddoblevela set 
               NombreCorto='$nombrecorto',
               Modelo='$modelo',
               Descrpciondelproducto='$descripcion',
               Descripciondelartículo='$descrip',
               fe_edicion='$fe_alta'
               WHERE numerodearticulo='$clave' AND color='$color' AND Activo='Activo' ";
            //mysql_query($ssql,$link);
            $mysqli->query($ssql);


    }


        //echo "<p>$query;</p>";
    }

    //exit();
}

mysqli_close($mysqli);

echo "Se actualizaron ".count($JsonResul['Resultado']) ." producto(s)";

}

}


//print_r($JsonResul);
//exit();

//$paramWs =array("Key"=>'iknKDpgjsPUmXlIvX2lkeQ==','Codigo'=>'ASP2020.02');
//$resul= $ws->GetProduct($paramWs);

//print_r($resul);
//$Data =$resul->GetProductResult;



//$JsonResul = json_decode($Data);
//print_r($JsonResul);
?>
