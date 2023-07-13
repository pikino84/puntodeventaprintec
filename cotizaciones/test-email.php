<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/inc/phpmailer_v5_1/class.phpmailer.php');

$mail=new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth=true;                  // enable SMTP authentication
$mail->Host="smtp.sendgrid.net"; // sets the SMTP server
$mail->Port=587;                    // set the SMTP port for the GMAIL server
$mail->SMTPSecure = "tls";  
$mail->Username="apikey"; // SMTP account username
$mail->Password="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";        // SMTP account password
$mail->SetFrom('eduardo@printec.mx');

$mail->Subject='Test';
$mail->MsgHTML('<p>Test</p>');
$mail->AddAddress('acaamal@gmail.com');
                      

$mail->Send();


//printecsend
//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


?>