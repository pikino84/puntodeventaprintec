<?php
error_reporting(0);

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
$emailcliente="";
$incluye_iva=1;
$incluye_urgencia=0;


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
                                cc.email AS email,
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
                                ifnull(c.incluye_urgencia,0) as incluye_urgencia,
                                e.logo_cotizacion AS logo,
                                a.modelo as modelo,
                                e.nombre as empresa,
                                p.tipo as tipo,
                                ifnull(a.precio_porunidad,0) as precio_porunidad,
                                s.email as sucursalemail,
                                u.email as vendedoremail,
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
                                    $empresa='';
                                    $sucursalemail='';
                                    $vendedoremail='';
                                    $incluye_urgencia=0;


                            		$rs->data_seek(0);                             
                                    while($dt=$rs->fetch_assoc()){

                                        //$total+=$dt['total'];
                                        $logo=$dt['logo'];
                                        $empresa=$dt['empresa'];
                                        $sucursalemail=$dt['sucursalemail'];
                                        $vendedoremail=$dt['vendedoremail'];
                                        $incluye_iva=$dt['incluye_iva'];
                                        $incluye_urgencia=$dt['incluye_urgencia'];

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

                                            if($dt['tipo']=='N'){

                                                $fotoarticulo = "https://www.doblevela.com/images/".str_replace(" ","",$dt['modelo']).".png";

                                                if (!file_exists($fotoarticulo))
                                                      $fotoarticulo = str_replace(".png",".jpg",$fotoarticulo);

                                                if (!file_exists($fotoarticulo))
                                                      $fotoarticulo = "https://www.doblevela.com/images/large/".str_replace(" ","",$dt['modelo'])."_lrg.jpg";
                                                
                                                $imgprod="<img src='$fotoarticulo' width='100' />";
                                            }else
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
                                    $asunto=$dt['asunto'];

                            	}

                                if($incluye_urgencia==1){
                                    $subTotal=$subTotal+($subTotal*.3);
                                }

                                if($incluye_envio==1)
                                    $textos .="<tr><td>* El precio reflejado incluye envio</td></tr>";
                                else
                                    $textos .="<tr><td>* El precio reflejado no incluye envio</td></tr>";
                                                               
                                $textos .="<tr><td>* Tiempo de entrega ".$dias_entrega." días hábiles  a partir de la recepción del pago. </td></tr>";

                                if($incluye_urgencia==1){
                                    $textos .="<tr><td>* El precio reflejado incluye entrega por urgencia</td></tr>";
                                }

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
                                                <td colspan='6' align='right'><strong>Retención ISR</strong></td>
                                                <td><strong>".number_format(($subTotal*(1.25/100)),2)."</strong></td>
                                            </tr>
                                            <tr>
                                                <td colspan='6' align='right'><strong>Total a pagar</strong></td>
                                                <td><strong>".number_format(($subTotal*1.16)-($subTotal*(1.25/100)),2)."</strong></td>
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
<table width='600'>
<tr>
<td width='40%'><img src='https://puntodeventa.printec.mx/img/".$logo."' width='200px'/></td>
<td>
<table align='right' style='font-size:12px'>
<tr>
<td><span style='color:#234d7d'>COTIZACIÓN:</span></td>
<td>PRINT$id</td>
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
<td><span style='color:#234d7d'>OBSERVACIONES</span></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><td>* Cotización sujeta a confirmación de existencia por almacén.</td></tr>
$textos
<tr><td>* Si requiere entrega a un plazo menor aplicara un cargo adicional del 30%</td></tr>
</table>
</html>
</body>
";

//echo $html;
//exit();

require_once($_SERVER['DOCUMENT_ROOT'].'/inc/phpmailer_v5_1/class.phpmailer.php');

$email_respaldo = "info@printec.mx";
$admin_name = "Cotización ".$empresa;

if($asunto=='')
    $asunto = "Verificar almacén - ".$empresa;
else
    $asunto = "Verificar almacén ".$asunto." - ".$empresa;

$mail=new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth=true;                  // enable SMTP authentication
//$mail->Host="smtp.gmail.com"; // sets the SMTP server
$mail->Host="smtp.sendgrid.net"; // sets the SMTP server
$mail->Port=587; 
$mail->SMTPSecure = "tls"; 
//$mail->Username="envios.printec@gmail.com"; // SMTP account username
//$mail->Password="SG.tKmp@20";        // SMTP account password
$mail->Username="apikey"; // SMTP account username
$mail->Password="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";        // SMTP account password
$mail->SetFrom($vendedoremail,$admin_name);
$mail->Subject=$asunto;
$mail->MsgHTML($html);

$mail->AddAddress("stock@printec.mx"); 
$mail->AddReplyTo('info@printec.mx', 'Cotización '.$empresa);
//$mail->AddBCC("eduardo@printec.mx"); 
$mail->AddBCC($email_respaldo);
//$mail->AddBCC("ventas@printec.mx"); 
//$mail->AddBCC($sucursalemail);
//$mail->AddBCC($vendedoremail);
//$mail->AddBCC("envios.printec@gmail.com");
        
//if($mail->Send()) 
//    echo "OK";
//else
//    echo $mail­->ErrorInfo;               

$mail->Send();

//echo $resp;

$ssql="update ccotizacion set cerrado=1 where folio=$id ";
$link->query($ssql);

header("Location: /cotizaciones/detalle.php?id=$id&evt=V&cw=1");
exit();
?>