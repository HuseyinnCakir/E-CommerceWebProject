<?php
include '../nedmin/netting/baglan.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if ($_POST['toplam']!=$_POST['islem']) {
	
	echo "bot kontrolü";
	exit;
} 
	$email=htmlspecialchars($_POST["email"]);
	$adsoyad=htmlspecialchars($_POST["adsoyad"]);
	$mesaj=htmlspecialchars($_POST["mesaj"]);
	//Mysql den mail bilgilerimizi çekiyoruz.
	$ayarsor=$db->prepare("SELECT * FROM ayar where ayar_id=?");
	$ayarsor->execute(array(0));
	$ayarcek=$ayarsor->fetch(PDO::FETCH_ASSOC);

	$mail = new PHPMailer();

try{
	$mail->IsSMTP();  
	$mail->CharSet='UTF-8';                               // send via SMTP
	$mail->Host     = $ayarcek['ayar_smtphost']; // SMTP servers
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = $ayarcek['ayar_smtpuser'];  // SMTP username
	$mail->Password = $ayarcek['ayar_smtppassword'];// SMTP password
	$mail->Port     = $ayarcek['ayar_smtpport'];
	$mail->SMTPSecure = 'lts';  
	$mail->From     = $ayarcek['ayar_smtpuser'];
	$mail->Fromname = "Test Mail";
	//Çoklu mail için bu satırı çoğalt
	$mail->setFrom($email, $adsoyad);
	$mail->AddAddress($ayarcek['ayar_smtpuser'], 'Hüseyin Çakır');
    $mail->isHTML(true); 
	$mail->Subject  =  $adsoyad;
	$mail->Body     =  $mesaj;
	
    $mail->send();
 
	echo "Mesaj Gönderildi";
	
}

catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
 echo $e->getMessage(); //Boring error messages from anything else!
}

?>
