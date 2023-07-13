<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$html="<p>TEST</p>";
$empresa="Printec";
$asunto='Test';

require_once($_SERVER['DOCUMENT_ROOT'].'/inc/phpmailer_v5_1/class.phpmailer.php');

$email_respaldo = "info@printec.mx";
$vendedoremail = "info@printec.mx";
$admin_name = "Cotización ".$empresa;

if($asunto=='')
    $asunto = "Verificar almacén - ".$empresa;
else
    $asunto = "Verificar almacén ".$asunto." - ".$empresa;

$mail=new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth=true;                  // enable SMTP authentication
$mail->Host="smtp.hostinger.mx"; // sets the SMTP server
$mail->Port=587;                    // set the SMTP port for the GMAIL server
$mail->SMTPSecure = "tls"; 
$mail->Username="webmaster@printec.mx"; // SMTP account username
$mail->Password="Printec+2020";        // SMTP account password
$mail->SetFrom($vendedoremail,$admin_name);
$mail->Subject=$asunto;
$mail->MsgHTML($html);
$mail->SMTPDebug  = 1;
//$mail->AddAddress($email); 

//$mail->AddAddress($emailcliente); 
$mail->AddAddress("eduardo@printec.mx","Eduardo"); 
//$mail->AddAddress("acaamal@gmail.com");
$mail->AddReplyTo('info@printec.mx', 'Cotización '.$empresa);
//$mail->AddBCC("eduardo@printec.mx"); 
$mail->AddBCC("acaamal@gmail.com","Azael"); 
                          

$mail->Send();

echo $mail->ErrorInfo;


echo "Listo";
?>