<?php 

ob_start();
session_start();

include 'baglan.php';
$urun_id=$_POST['urun_id'];
if (!empty($_FILES)) {
 // yeni resim eklenmiş mii ?
  

  if($_FILES['file']['size']> 177058){
    //echo "Dosya boyutu 1mb'dan fazla";
  
    //Header("Location:../production/urun-foto-yukle.php?urun_id=$urun_id&durum=dosyaboyutubuyuk");
    exit;
  }
  $izinli_uzantilar=['jpg','png','gif'];

  $uzanti=strtolower(substr($_FILES['file']["name"],strpos($_FILES['file']["name"],'.')+1));

  if(in_array($uzanti, $izinli_uzantilar) === false){ 
    //echo "Kabul edilmeyen uzantı";

   // Header("Location:../production/urun-foto-yukle.php?urun_id=$urun_id&durum=kabuledilmeyenuzanti");
    exit;

  }


	$uploads_dir = '../../dimg/urun';
	@$tmp_name = $_FILES['file']["tmp_name"];
	@$name = $_FILES['file']["name"];
	$benzersizsayi1=rand(20000,32000);
	$benzersizsayi2=rand(20000,32000);
	$benzersizsayi3=rand(20000,32000);
	$benzersizsayi4=rand(20000,32000);

	$benzersizad=$benzersizsayi1.$benzersizsayi2.$benzersizsayi3.$benzersizsayi4;
	$refimgyol=substr($uploads_dir, 6)."/".$benzersizad.$name;
	@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");

	

	$kaydet=$db->prepare("INSERT INTO urunfoto SET
		urunfoto_resimyol=:resimyol,
		urun_id=:urun_id");
	$insert=$kaydet->execute(array(
		'resimyol' => $refimgyol,
		'urun_id' => $urun_id
		));




}





?>