<pre>
<?php
$account="FortressMarket@gmail.com";
$password="FMISN2017";
$from="FortressMarket@gmail.com";
$to="amatokus8669@gmail.com";
$from_name="FMISN";
$msg="<strong>This is a bold text.</strong>"; // HTML message
$subject="HTML message";

include("mail/PHPMailerAutoload.php");

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 1;
$mail->SMTPAuth= true;
$mail->SMTPSecure = 'ssl';
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // Or 587
$mail->isHTML(true);
$mail->Username= $account;
$mail->Password= $password;
$mail->SetFrom = $from;
$mail->FromName= $from_name;
$mail->Subject = $subject;
$mail->Body = $msg;
$mail->addAddress($to);

if(!$mail->send()){
 echo "Mailer Error: " . $mail->ErrorInfo;
}else{
 echo "E-Mail has been sent";
}
 ?>
 </pre>