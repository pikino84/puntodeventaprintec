<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id=0;

    if($_GET){
        if($_GET['evt']=='V'&&isset($_GET['id'])){
            $id=intval($_GET['id']);
        }
    }

    require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');

	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	restrinct();

	$ln = ln();

require $_SERVER['DOCUMENT_ROOT'].'/inc/mpdf/mpdf.php';

$id=0;

    if($_GET){
        if($_GET['evt']=='V'&&isset($_GET['id'])){
            $id=intval($_GET['id']);
        }
    }

$fecha= date("d/m/Y");
$filas="";
$textos="";
$email="";
$nombre="";
$copiacorporativo=0;
$foliocliente=0;
$asunto='';
$incluye_iva=1;


$ssql = " 
                                SELECT
                                a.folio,
                                IFNULL(a.descrip,p.descrip) AS Descripcion,
                                a.precio,
                                a.descuento,
                                a.cantidad,
                                (a.precio - a.descuento)*a.cantidad AS total,
                                IFNULL(c.incluye_envio,0) AS incluye_envio,
                                IFNULL(c.requiere_logo,0) AS requiere_logo,
                                IFNULL(c.dias_entrega,15) AS dias_entrega,
                                IFNULL(c.copiacorporativo,0) AS copiacorporativo,                                                                
                                cc.email AS email,
                                cc.nombre AS nombre,
                                cc.apellido AS apellido,
                                cc.telefono AS telefono,
                                cc.folio AS foliocliente,
                                IFNULL(c.asunto,'') AS asunto,
                                c.cerrado,
                                a.sku AS clave,
                                a.color AS color,
                                a.personalizacionlista AS personalizacionlista,
                                a.personalizacion AS personalizacion,
                                a.precioexterno AS precioexterno,
                                a.preciopersonalizacion AS preciopersonalizacion,
                                a.numerotintas AS numerotintas,
                                a.precionumerotintas AS precionumerotintas,
                                a.aplicaimpresiontinta AS aplicaimpresiontinta,
                                IFNULL(c.interna,0) AS interna,
                                ifnull(c.incluye_iva,0) as incluye_iva,
                                e.logo_cotizacion AS logo,
                                a.modelo as modelo,
                                s.nombre as sucursal,
                                s.email as sucursalemail,
                                s.telefono as sucursaltel,
                                s.banco as banco,
                                s.cuenta as cuenta,
                                s.clabe as clabe,
                                s.num_tarjeta as num_tarjeta,
                                s.rfc as rfc,
                                s.facebook as facebook,
                                s.twitter as twitter,
                                s.sitio as sitio,
                                e.nombre as empresa,
                                u.nombre as vendedor,
                                u.email as vendedoremail,
                                u.telefono as vendedortel,
                                p.tipo as tipo,
                                ifnull(a.precio_porunidad,0) as precio_porunidad,
                                CASE WHEN IFNULL(p.Imagen,'')=''
                                THEN 'https://puntodeventa.printec.mx/img/servicio-especial/servicio-especial.jpg'
                                ELSE 
                                CONCAT('https://puntodeventa.printec.mx/img/servicio-especial/',p.Imagen)
                                END
                                AS Imagen
                                FROM tblcotizaciondet a
                                INNER JOIN ccotizacion c ON a.ccotizacion_folio=c.folio
                                LEFT JOIN ccliente cc ON c.ccliente_folio=cc.folio
                                INNER JOIN cproducto p ON a.cproducto_folio=p.folio
                                INNER JOIN cusuario u ON c.cusuario_folio=u.folio
                                INNER JOIN csucursal s ON u.csucursal_folio=s.folio
                                INNER JOIN cempresa e ON s.cempresa_folio=e.folio
                                WHERE a.sta=1
                                AND a.ccotizacion_folio=$id                            
                                ORDER BY a.folio DESC 
                                                ";
						$rs = $link->query($ssql);

						if($rs->num_rows>0){

                                    $total=0;
                                    $incluye_envio=0;
                                    $dias_entrega=15;
                                    $requiere_logo=0;
                                    $asunto='';
                                    $subTotal=0;
                                    $logo='';
                                    $sucursal='';
                                    $sucursalemail='';
                                    $sucursaltel='';
                                    $banco='';
                                    $cuenta='';
                                    $clabe='';
                                    $facebook='';
                                    $twitter='';
                                    $sitio='';
                                    $empresa='';
                                    $vendedor='';
                                    $vendedoremail='';
                                    $vendedortel='';
                                    $num_tarjeta='';
                                    $rfc='';

                            		$rs->data_seek(0);                             
                                    while($dt=$rs->fetch_assoc()){

                                        $logo=$dt['logo'];

                                        $sucursal=$dt['sucursal'];
                                        $sucursalemail=$dt['sucursalemail'];
                                        $sucursaltel=$dt['sucursaltel'];
                                        $banco=$dt['banco'];
                                        $cuenta=$dt['cuenta'];
                                        $clabe=$dt['clabe'];
                                        $num_tarjeta=$dt['num_tarjeta'];
                                        $rfc=$dt['rfc'];
                                        $facebook=$dt['facebook'];
                                        $twitter=$dt['twitter'];
                                        $sitio=$dt['sitio'];
                                        $empresa=$dt['empresa'];
                                        $vendedor=$dt['vendedor'];
                                        $vendedoremail=$dt['vendedoremail'];
                                        $vendedortel=$dt['vendedortel'];
                                        $incluye_iva=$dt['incluye_iva'];
                                        $precioproductoli = 0;

                                                if($dt['interna']==1&&$dt['precioexterno']>0)
                                                    $precioproductoli = $dt['precioexterno'];
                                                 else
                                                    $precioproductoli = $dt['precio'];

                                            if($dt['precio_porunidad']==1)
                                                $costo = ($dt['cantidad']*$precioproductoli)+($dt['cantidad']*$dt['preciopersonalizacion']*$dt['precionumerotintas']);
                                            else
                                                $costo = ($dt['cantidad']*$precioproductoli)+($dt['preciopersonalizacion']*$dt['precionumerotintas']);


                                            $subTotal+=$costo;

                                            if($dt['tipo']=='N')
                                                //$imgprod="<img src='http://imagenes.printec.mx/".$dt['modelo']."_lrg.jpg' width='100' />";
                                                $imgprod="<img src='https://www.doblevela.com/images/".$dt['modelo'].".png' width='100' />";
                                            else
                                                $imgprod="<img src='".$dt['Imagen']."' width='100' />";

                                        $filas .="<tr> 
                                    <td align='center'>".$imgprod."
                                        <br/>".$dt['clave']."</td>
                                    <td>".$dt['Descripcion']."</td>
                                    <td>".$dt['color']."</td>
                                    <td>".$dt['personalizacion']." ".$dt['numerotintas']."</td>
                                    <td>".$dt['cantidad']."</td>                                    
                                    <td>".$precioproductoli."</td>                                    
                                    <td>".number_format($costo,2)."</td>    
                                </tr>";   

                            	   $incluye_envio=$dt['incluye_envio'];
                                    $dias_entrega=$dt['dias_entrega'];
                                    $requiere_logo=$dt['requiere_logo'];  
                                    $emailcliente=$dt['email'];
                                    $nombre=$dt['nombre'];                        
                                    $apellido=$dt['apellido'];
                                    $telefono=$dt['telefono'];
                                    $copiacorporativo=$dt['copiacorporativo'];
                                    $foliocliente=$dt['foliocliente'];
                                    $asunto=$dt['asunto'];


                                }

                                if($incluye_envio==1)
                                    $textos .="<tr><td>* El precio reflejado incluye envio</td></tr>";
                                else
                                    $textos .="<tr><td>* El precio reflejado no incluye envio</td></tr>";
                                
                                    $textos .="<tr><td>* Tiempo de entrega ".$dias_entrega." días hábiles  a partir de la recepción del pago. </td></tr>";

                                if($requiere_logo==1)
                                    $textos .="<tr><td>* Se requiere el logo en vectores</td></tr>";

                                if($incluye_iva==1){
                                    $totales="<tr>
                                                <td colspan='6' align='right'><strong>Subtotal</strong></td>
                                                <td><strong>".number_format($subTotal,2)."</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan='6' align='right'><strong>Iva</strong></td>
                                                <td><strong>".number_format(($subTotal*1.16)-$subTotal,2)."</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan='6' align='right'><strong>Total a pagar</strong></td>
                                                <td><strong>".number_format(($subTotal*1.16),2)."</strong></td>
                                            </tr>";
                                }else{
                                    $totales="<tr>
                                                <td colspan='6' align='right'><strong>Total a pagar</strong></td>
                                                <td><strong>".number_format($subTotal,2)."</strong></td>
                                            </tr>";
                                }


                            	} 


$html = "
<html>
<body>
<table width='100%'>
<tr>
<td width='40%'><img src='https://puntodeventa.printec.mx/img/".$logo."' width='170px'/></td>
<td>
<table align='right' style='font-size:12px'>
<tr>
<td><span style='color:#234d7d'>CLIENTE:</span></td>
<td>$nombre $apellido </td>
</tr>
<tr>
<td><span style='color:#234d7d'>E-MAIL:</span></td>
<td>$emailcliente </td>
</tr>
<tr>
<td><span style='color:#234d7d'>TELEFONO:</span></td>
<td>$telefono </td>
</tr>
<tr>
<td><span style='color:#234d7d'>FECHA:</span></td>
<td>".$fecha." </td>
</tr>
</table>
<td>
</tr>
</table>
<br/><br/>
<table width='100%' style='font-size:11px'>
<tr>
<th>ClAVE</th>                            
<th>CONCEPTO</th>
<th>COLOR</th>
<th>PERSONALIZACION</th>
<th>CANTIDAD</th>
<td>PRECIO UNITARIO</td>
<td>TOTAL</td>
</tr>
$filas
$totales
</table>
<br/><br/>
<table style='font-size:12px'>
<tr>
<td><span style='color:#234d7d'>CONDICIONES DE PAGO</span></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><td>* Ésta cotización tiene una vigencia de 8 días.</td></tr>
$textos
<tr><td>* Si requiere entrega a un plazo menor aplicara un cargo adicional del 30%</td></tr>
</table>
<br>
<table width='100%' style='font-size:12px'>
<tr>
<td width='70%'></td>
<td>______________________________</td>
</tr>
<tr>
<td width='70%'><span style='color:#234d7d'>DATOS PARA REALIZAR PAGO CON FACTURA</span></td>
<td>FIRMA DE CONFORMIDAD  </td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr><td>BENEFICIARIO:   ".$sucursal." </td><td></td></tr>
<tr><td>RFC:  ".$rfc."<td><td></td></tr>
<tr><td>BANCO:  ".$banco."<td><td></td></tr>
<tr><td>CUENTA PESOS M.N.: ".$cuenta."</td><td></td></tr>
<tr><td>NUMERO DE TARJETA M.N.: ".$num_tarjeta."</td><td></td></tr>
<tr><td>CLABE PESOS M.N.:  ".$clabe." </td><td></td></tr>
</table>
</html>
</body>
";



$ssql="update ccotizacion set cerrado=1 where folio=$id ";
$link->query($ssql);

$cabecera = "<span style='color:#234d7d;font-size:11px'><strong>COTIZACION PRINT$id</strong></span>";
        $pie = "<span style='color:#234d7d;font-size:11px'>".$empresa." ".date("Y")." PROMOCIONAMOS TU MARCA, ES UNA MARCA REGISTRADA</span>";
        $mpdf=new mPDF(['debug' => true]);
        $mpdf->SetHTMLHeader($cabecera);
        $mpdf->SetHTMLFooter($pie);

$mpdf->WriteHTML($html,2);             
//$mpdf->Output("Cotizacion-PRINT".$id.".pdf",'I');
$mpdf->debug = true; 
$mpdf->showImageErrors = true;

//$mpdf->Output("Cotizacion-PRINT".$id.".pdf",'I')
$filename = "Cotizacion-PRINT".$id.".pdf";
$mpdf->Output($filename,'F');
?>