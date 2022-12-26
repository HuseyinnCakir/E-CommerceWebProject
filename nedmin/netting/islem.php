<?php
ob_start();
session_start();
include 'baglan.php';
include '../production/fonksiyon.php';


if (isset($_POST['kullanicikaydet'])) {

  echo $kullanici_adsoyad=htmlspecialchars($_POST['kullanici_adsoyad']); echo "<br>"; // güvenlik açığını engellemek adına script yazılamaması adına htmlspecialchars fonk kullanıyoruz.
  echo $kullanici_mail=htmlspecialchars($_POST['kullanici_mail']); echo "<br>";
  echo $kullanici_passwordone=trim($_POST['kullanici_passwordone']); echo "<br>";
  echo $kullanici_passwordtwo=trim($_POST['kullanici_passwordtwo']); echo "<br>";
  if ($kullanici_passwordone==$kullanici_passwordtwo) {
    if (strlen($kullanici_passwordone)>=6) {
// Başlangıç
      $kullanicisor=$db->prepare("select * from kullanici where kullanici_mail=:mail");
      $kullanicisor->execute([
        'mail' => $kullanici_mail
        ]);
      //dönen satır sayısını belirtir
      $say=$kullanicisor->rowCount();
      if ($say==0) {
        //md5 fonksiyonu şifreyi md5 şifreli hale getirir.
        $password=md5($kullanici_passwordone);
        $kullanici_yetki=1;
      //Kullanıcı kayıt işlemi yapılıyor...
        $kullanicikaydet=$db->prepare("INSERT INTO kullanici SET
          kullanici_adsoyad=:kullanici_adsoyad,
          kullanici_mail=:kullanici_mail,
          kullanici_password=:kullanici_password,
          kullanici_yetki=:kullanici_yetki
          ");
        $insert=$kullanicikaydet->execute([
          'kullanici_adsoyad' => $kullanici_adsoyad,
          'kullanici_mail' => $kullanici_mail,
          'kullanici_password' => $password,
          'kullanici_yetki' => $kullanici_yetki
          ]);
        
        if ($insert) {

          $_SESSION['userkullanici_mail']=$kullanici_mail;
          header("Location:../../");
        } else {
          header("Location:../../register.php?durum=basarisiz");
        }
      } else {

        header("Location:../../register.php?durum=mukerrerkayit");
      }

    // Bitiiş

    } else {
      header("Location:../../register.php?durum=eksiksifre");
    }
  } else {

    header("Location:../../register.php?durum=farklisifre");
  }

}

if (isset($_POST['kullanicigiris'])) {


  
  $kullanici_mail=htmlspecialchars($_POST['kullanici_mail']); 
  $kullanici_password=md5($_POST['kullanici_password']); 



  $kullanicisor=$db->prepare("select * from kullanici where kullanici_mail=:mail and kullanici_yetki=:yetki and kullanici_password=:password and kullanici_durum=:durum");
  $kullanicisor->execute(array(
    'mail' => $kullanici_mail,
    'yetki' => 1,
    'password' => $kullanici_password,
    'durum' => 1
    ));


  $say=$kullanicisor->rowCount();



  if ($say==1) {

    $_SESSION['userkullanici_mail']=$kullanici_mail;

    header("Location:../../");
    exit;
    




  } else {


    header("Location:../../?durum=basarisizgiris");

  }


}





if(isset($_POST['admingiris'])){
  $kullanici_mail=$_POST['kullanici_mail'];
  $kullanici_password=md5($_POST['kullanici_password']);

  $kullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_mail=:mail and kullanici_password=:password and kullanici_yetki=:yetki");
  $kullanicisor->execute([
    'mail' => $kullanici_mail,
    'password' => $kullanici_password,
    'yetki' => 5
  ]);

  echo $kontrol=$kullanicisor->rowCount();

  if($kontrol==1){

    $_SESSION['kullanici_mail']=$kullanici_mail;
    header("Location:../production/index.php");
  }
  else{

    header("Location:../production/login.php?durum=no");
    exit;
  }

}

if(isset($_POST['adminduzenle'])){

  $kullanici_id=$_POST['kullanici_id'];
//TABLO GÜNCELLEME KODLARI
  $ayarkaydet=$db->prepare("UPDATE kullanici SET

    kullanici_tc=:kullanici_tc,
    kullanici_adsoyad=:kullanici_adsoyad,
    kullanici_durum=:kullanici_durum
    WHERE kullanici_id={$_POST['kullanici_id']}");

  $update=$ayarkaydet->execute(
    ['kullanici_tc' => $_POST['kullanici_tc'],
    'kullanici_adsoyad' => $_POST['kullanici_adsoyad'],
    'kullanici_durum' => $_POST['kullanici_durum']
  ]);



  if($update){
  //echo "güncelleme başarılı";
    header("Location:../production/kullanici-duzenle.php?kullanici_id=$kullanici_id&durum=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
    header("Location:../production/kullanici-duzenle.php?kullanici_id=$kullanici_id&durum=no");
    exit;
  }


}


if(isset($_POST['kullaniciduzenle'])){

 $kullanici_id=$_POST['kullanici_id']; 

 
//TABLO GÜNCELLEME KODLARI
  $ayarkaydet=$db->prepare("UPDATE kullanici SET

    kullanici_tc=:kullanici_tc,
    kullanici_adsoyad=:kullanici_adsoyad,
    kullanici_gsm=:kullanici_gsm,
    kullanici_adres=:kullanici_adres
    WHERE kullanici_id={$_POST['kullanici_id']}");

  $update=$ayarkaydet->execute(
    ['kullanici_tc' => $_POST['kullanici_tc'],
    'kullanici_adsoyad' => $_POST['kullanici_adsoyad'],
    'kullanici_gsm' => $_POST['kullanici_gsm'],
    'kullanici_adres' => $_POST['kullanici_adres']
  ]);



  if($update){
  //echo "güncelleme başarılı";
    header("Location:../../hesabim.php?kullanici_id=$kullanici_id&durum=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
    header("Location:../../hesabim.php?kullanici_id=$kullanici_id&durum=no");
    exit;
  }


}



if(isset($_POST['menuduzenle'])){

  $menu_id=$_POST['menu_id'];
  $menu_seourl=seo($_POST['menu_ad']);


//TABLO GÜNCELLEME KODLARI
  $ayarkaydet=$db->prepare("UPDATE menu SET

    menu_ad=:menu_ad,
    menu_detay=:menu_detay,
    menu_url=:menu_url,
    menu_sira=:menu_sira,
    menu_seourl=:menu_seourl,
    menu_durum=:menu_durum
    WHERE menu_id={$_POST['menu_id']}");

  $update=$ayarkaydet->execute(
    ['menu_ad' => $_POST['menu_ad'],
    'menu_detay' => $_POST['menu_detay'],
    'menu_url' => $_POST['menu_url'],
    'menu_sira' => $_POST['menu_sira'],
    'menu_seourl' => $menu_seourl,
    'menu_durum' => $_POST['menu_durum']
  ]);



  if($update){
  //echo "güncelleme başarılı";
    header("Location:../production/menu-duzenle.php?menu_id=$menu_id&durum=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
   header("Location:../production/menu-duzenle.php?menu_id=$menu_id&durum=no");
   exit;
 }


}


if (isset($_POST['menukaydet'])) {


  $menu_seourl=seo($_POST['menu_ad']);


  $ayarekle=$db->prepare("INSERT INTO menu SET
    menu_ad=:menu_ad,
    menu_detay=:menu_detay,
    menu_url=:menu_url,
    menu_sira=:menu_sira,
    menu_seourl=:menu_seourl,
    menu_durum=:menu_durum
    ");

  $insert=$ayarekle->execute(array(
    'menu_ad' => $_POST['menu_ad'],
    'menu_detay' => $_POST['menu_detay'],
    'menu_url' => $_POST['menu_url'],
    'menu_sira' => $_POST['menu_sira'],
    'menu_seourl' => $menu_seourl,
    'menu_durum' => $_POST['menu_durum']
  ));


  if ($insert) {

    Header("Location:../production/menu.php?durum=ok");

  } else {

    Header("Location:../production/menu.php?durum=no");
  }

}





if (isset($_POST['logoduzenle'])) {

  if($_FILES['ayar_logo']['size']> 177058){
    //echo "Dosya boyutu 1mb'dan fazla";
  
    Header("Location:../production/genel-ayar.php?durum=dosyaboyutubuyuk");
    exit;
  }
  $izinli_uzantilar=['jpg','png','gif'];

  $uzanti=strtolower(substr($_FILES['ayar_logo']["name"],strpos($_FILES['ayar_logo']["name"],'.')+1));

  if(in_array($uzanti, $izinli_uzantilar) === false){ 
    //echo "Kabul edilmeyen uzantı";

    Header("Location:../production/genel-ayar.php?durum=kabuledilmeyenuzanti");
    exit;

  }

  $uploads_dir = '../../dimg';

  @$tmp_name = $_FILES['ayar_logo']["tmp_name"]; //ön belleğe alma işlemi
  @$name = $_FILES['ayar_logo']["name"]; // gelen logonun adını alma işlemi


  $benzersizsayi=rand(20000,32000); // benzersiz sayı oluşturuyoruz. dosyayı kaydederken adlarında çakışma olmasın diye.
  $refimgyol=substr($uploads_dir, 6)."/".$benzersizsayi.$name; //belirlenen satırdan sonrasını göstermek için substr kullandım. $uploads_dir'in içindeki "../../" şu kısmı kesmek için yaptım.

  @move_uploaded_file($tmp_name, "$uploads_dir/$benzersizsayi$name"); // dosyayı lokalde kaydetme.

  
  $duzenle=$db->prepare("UPDATE ayar SET
    ayar_logo=:logo
    WHERE ayar_id=0");
  $update=$duzenle->execute(array(
    'logo' => $refimgyol
  ));



  if ($update) {

    $resimsilunlink=$_POST['eski_yol'];
    unlink("../../$resimsilunlink");  // eski logoyu dosyalardan silme işlemi.

    Header("Location:../production/genel-ayar.php?durum=ok");

  } else {

    Header("Location:../production/genel-ayar.php?durum=no");
  }

}



if (isset($_POST['kullaniciprofilfotosuduzenle'])) {

$kullanici_id=$_POST['kullanici_id']; 



if($_FILES['kullanici_resim']['size']> 177058){
    //echo "Dosya boyutu 1mb'dan fazla";
  
    Header("Location:../production/kullanici-duzenle.php?durum=dosyaboyutubuyuk");
    exit;
  }
  $izinli_uzantilar=['jpg','png','gif'];

  $uzanti=strtolower(substr($_FILES['kullanici_resim']["name"],strpos($_FILES['kullanici_resim']["name"],'.')+1));

  if(in_array($uzanti, $izinli_uzantilar) === false){ 
    //echo "Kabul edilmeyen uzantı";

    Header("Location:../production/kullanici-duzenle.php?durum=kabuledilmeyenuzanti");
    exit;

  }

  $uploads_dir = '../../dimg/AdminPaneliProfilFotolari';

  @$tmp_name = $_FILES['kullanici_resim']["tmp_name"]; //ön belleğe alma işlemi
  @$name = $_FILES['kullanici_resim']["name"]; // gelen logonun adını alma işlemi


  $benzersizsayi=rand(20000,32000); // benzersiz sayı oluşturuyoruz. dosyayı kaydederken adlarında çakışma olmasın diye.
  $refimgyol=substr($uploads_dir, 6)."/".$benzersizsayi.$name; //belirlenen satırdan sonrasını göstermek için substr kullandım. $uploads_dir'in içindeki "../../" şu kısmı kesmek için yaptım.

  @move_uploaded_file($tmp_name, "$uploads_dir/$benzersizsayi$name"); // dosyayı lokalde kaydetme.

  
  $duzenle=$db->prepare("UPDATE kullanici SET
    kullanici_resim=:resim
    WHERE kullanici_id={$_POST['kullanici_id']}");
  $update=$duzenle->execute(array(
    'resim' => $refimgyol
  ));



  if ($update) {

    $resimsilunlink=$_POST['eski_yol'];
    unlink("../../$resimsilunlink");  // eski logoyu dosyalardan silme işlemi.

    Header("Location:../production/kullanici-duzenle.php?durum=ok");

  } else {

    Header("Location:../production/kullanici-duzenle.php?durum=ok");
  }

}





if (isset($_POST['sliderkaydet'])) {

if($_FILES['slider_resimyol']['size']> 177058){
    //echo "Dosya boyutu 1mb'dan fazla";
  
    Header("Location:../production/slider.php?durum=dosyaboyutubuyuk");
    exit;
  }
  $izinli_uzantilar=['jpg','png','gif'];

  $uzanti=strtolower(substr($_FILES['slider_resimyol']["name"],strpos($_FILES['slider_resimyol']["name"],'.')+1));

  if(in_array($uzanti, $izinli_uzantilar) === false){ 
    //echo "Kabul edilmeyen uzantı";

    Header("Location:../production/slider.php?durum=kabuledilmeyenuzanti");
    exit;

  }

  $uploads_dir = '../../dimg/slider';

  @$tmp_name = $_FILES['slider_resimyol']["tmp_name"]; //ön belleğe alma işlemi
  @$name = $_FILES['slider_resimyol']["name"]; // gelen sliderin adını alma işlemi


  $benzersizsayi1=rand(20000,32000);// benzersiz sayı oluşturuyoruz. dosyayı kaydederken adlarında çakışma 
  $benzersizsayi2=rand(20000,32000); // olmasın diye.
  $benzersizsayi3=rand(20000,32000);
  $benzersizsayi4=rand(20000,32000);
  $benzersizad=$benzersizsayi1.$benzersizsayi2.$benzersizsayi3.$benzersizsayi4;
  $refimgyol=substr($uploads_dir, 6)."/".$benzersizad.$name; //belirlenen satırdan sonrasını göstermek için substr kullandım. $uploads_dir'in içindeki "../../" şu kısmı kesmek için yaptım.

  @move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name"); // dosyayı yükleme.

  


  $duzenle=$db->prepare("INSERT slider SET
    slider_ad=:slider_ad,
    slider_sira=:slider_sira,
    slider_link=:slider_link,
    slider_resimyol=:slider_resimyol");

  $insert=$duzenle->execute([
    'slider_ad' => $_POST['slider_ad'],
    'slider_sira' => $_POST['slider_sira'],
    'slider_link' => $_POST['slider_link'],
    'slider_resimyol' => $refimgyol ] );



  if ($insert) {

    Header("Location:../production/slider.php?durum=ok");}

    else {

      Header("Location:../production/slider.php?durum=no");
    }

  }

  if (isset($_POST['sliderduzenle'])) {

  
  if($_FILES['slider_resimyol']["size"] > 0)  {  // yeni resim eklenmiş mii ?
  

  if($_FILES['slider_resimyol']['size']> 177058){
    //echo "Dosya boyutu 1mb'dan fazla";
  
    Header("Location:../production/slider.php?durum=dosyaboyutubuyuk");
    exit;
  }
  $izinli_uzantilar=['jpg','png','gif'];

  $uzanti=strtolower(substr($_FILES['slider_resimyol']["name"],strpos($_FILES['slider_resimyol']["name"],'.')+1));

  if(in_array($uzanti, $izinli_uzantilar) === false){ 
    //echo "Kabul edilmeyen uzantı";

    Header("Location:../production/slider.php?durum=kabuledilmeyenuzanti");
    exit;

  }


    $uploads_dir = '../../dimg/slider';
    @$tmp_name = $_FILES['slider_resimyol']["tmp_name"];
    @$name = $_FILES['slider_resimyol']["name"];
    $benzersizsayi1=rand(20000,32000);
    $benzersizsayi2=rand(20000,32000);
    $benzersizsayi3=rand(20000,32000);
    $benzersizsayi4=rand(20000,32000);
    $benzersizad=$benzersizsayi1.$benzersizsayi2.$benzersizsayi3.$benzersizsayi4;
    $refimgyol=substr($uploads_dir, 6)."/".$benzersizad.$name;
    @move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");

    $duzenle=$db->prepare("UPDATE slider SET
      slider_ad=:ad,
      slider_link=:link,
      slider_sira=:sira,
      slider_durum=:durum,
      slider_resimyol=:resimyol 
      WHERE slider_id={$_POST['slider_id']}");
    $update=$duzenle->execute(array(
      'ad' => $_POST['slider_ad'],
      'link' => $_POST['slider_link'],
      'sira' => $_POST['slider_sira'],
      'durum' => $_POST['slider_durum'],
      'resimyol' => $refimgyol,
      ));
    

    $slider_id=$_POST['slider_id'];

    if ($update) {

      $resimsilunlink=$_POST['slider_resimyol'];
      unlink("../../$resimsilunlink");

      Header("Location:../production/slider-duzenle.php?slider_id=$slider_id&durum=ok");

    } else {

      Header("Location:../production/slider-duzenle.php?durum=no");
    }



  } else {   // yeni resim eklenmediyse resim harici diğer verileri güncelliyoruz.

    $duzenle=$db->prepare("UPDATE slider SET
      slider_ad=:ad,
      slider_link=:link,
      slider_sira=:sira,
      slider_durum=:durum   
      WHERE slider_id={$_POST['slider_id']}");
    $update=$duzenle->execute(array(
      'ad' => $_POST['slider_ad'],
      'link' => $_POST['slider_link'],
      'sira' => $_POST['slider_sira'],
      'durum' => $_POST['slider_durum']
      ));

    $slider_id=$_POST['slider_id'];

    if ($update) {

      Header("Location:../production/slider-duzenle.php?slider_id=$slider_id&durum=ok");

    } else {

      Header("Location:../production/slider-duzenle.php?durum=no");
    }
  }

}



if(!empty($_GET['slidersil'])){
  if($_GET['slidersil']=="ok"){

    islemkontrol();

    $sil=$db->prepare("DELETE from slider where slider_id=:id");
    $kontrol=$sil->execute(['id'=>$_GET['slider_id']]);


    if($kontrol){
  //echo "güncelleme başarılı";
      $resimsilunlink=$_GET['slider_resimyol'];
       unlink("../../$resimsilunlink");

      header("Location:../production/slider.php?sil=ok");
      exit;
    }
    else{
  //echo "güncelleme başarısız";
     header("Location:../production/slider.php?sil=no");
     exit;
   }
}

 }


if(!empty($_GET['kullanicisil'])){
 if($_GET['kullanicisil']=="ok"){

    islemkontrol();

  $sil=$db->prepare("DELETE from kullanici where kullanici_id=:id");
  $kontrol=$sil->execute(['id'=>$_GET['kullanici_id']]);


  if($kontrol){
  //echo "güncelleme başarılı";
    header("Location:../production/kullanici.php?sil=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
   header("Location:../production/kullanici.php?sil=no");
   exit;
 }
}

}
if(!empty($_GET['menusil'])){
if($_GET['menusil']=="ok"){

    islemkontrol();

  $sil=$db->prepare("DELETE from menu where menu_id=:id");
  $kontrol=$sil->execute(['id'=>$_GET['menu_id']]);


  if($kontrol){
  //echo "güncelleme başarılı";
    header("Location:../production/menu.php?sil=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
   header("Location:../production/menu.php?sil=no");
   exit;
 }


}
}



if(isset($_POST['genelayarkaydet'])){
//TABLO GÜNCELLEME KODLARI
  $ayarkaydet=$db->prepare("UPDATE ayar SET

    ayar_title=:ayar_title,
    ayar_description=:ayar_description,
    ayar_keywords=:ayar_keywords,
    ayar_author=:ayar_author WHERE ayar_id=0");

  $update=$ayarkaydet->execute(
    ['ayar_title' => $_POST['ayar_title'],
    'ayar_description' => $_POST['ayar_description'],
    'ayar_keywords' => $_POST['ayar_keywords'],
    'ayar_author' => $_POST['ayar_author']]);



  if($update){
  //echo "güncelleme başarılı";
    header("Location:../production/genel-ayar.php?durum=ok");
  }
  else{
  //echo "güncelleme başarısız";
    header("Location:../production/genel-ayar.php?durum=no");
  }


}




if(isset($_POST['iletisimayarkaydet'])){
//TABLO GÜNCELLEME KODLARI
  $ayarkaydet=$db->prepare("UPDATE ayar SET

    ayar_tel=:ayar_tel,
    ayar_gsm=:ayar_gsm,
    ayar_faks=:ayar_faks,
    ayar_mail=:ayar_mail,
    ayar_ilce=:ayar_ilce,
    ayar_il=:ayar_il,
    ayar_adres=:ayar_adres,
    ayar_mesai=:ayar_mesai
    WHERE ayar_id=0");

  $update=$ayarkaydet->execute(
    ['ayar_tel' => $_POST['ayar_tel'],
    'ayar_gsm' => $_POST['ayar_gsm'],
    'ayar_faks' => $_POST['ayar_faks'],
    'ayar_mail' => $_POST['ayar_mail'],
    'ayar_ilce' => $_POST['ayar_ilce'],
    'ayar_il' => $_POST['ayar_il'],
    'ayar_adres' => $_POST['ayar_adres'],
    'ayar_mesai' => $_POST['ayar_mesai']]);



  if($update){
  //echo "güncelleme başarılı";
    header("Location:../production/iletisim-ayarlar.php?durum=ok");
  }
  else{
  //echo "güncelleme başarısız";
    header("Location:../production/iletisim-ayarlar.php?durum=no");
  }


}
if(isset($_POST['apiayarkaydet'])){
//TABLO GÜNCELLEME KODLARI
  $ayarkaydet=$db->prepare("UPDATE ayar SET

    ayar_analystic=:ayar_analystic,
    ayar_maps=:ayar_maps,
    ayar_zopim=:ayar_zopim
    WHERE ayar_id=0");

  $update=$ayarkaydet->execute(
    ['ayar_analystic' => $_POST['ayar_analystic'],
    'ayar_maps' => $_POST['ayar_maps'],
    'ayar_zopim' => $_POST['ayar_zopim']
  ]);



  if($update){
  //echo "güncelleme başarılı";
    header("Location:../production/api-ayarlar.php?durum=ok");
  }
  else{
  //echo "güncelleme başarısız";
    header("Location:../production/api-ayarlar.php?durum=no");
  }


}



if(isset($_POST['sosyalayarkaydet'])){
//TABLO GÜNCELLEME KODLARI
  $ayarkaydet=$db->prepare("UPDATE ayar SET

    ayar_facebook=:ayar_facebook,
    ayar_twitter=:ayar_twitter,
    ayar_google=:ayar_google,
    ayar_youtube=:ayar_youtube
    WHERE ayar_id=0");

  $update=$ayarkaydet->execute(
    ['ayar_facebook' => $_POST['ayar_facebook'],
    'ayar_twitter' => $_POST['ayar_twitter'],
    'ayar_google' => $_POST['ayar_google'],
    'ayar_youtube' => $_POST['ayar_youtube']
  ]);



  if($update){
  //echo "güncelleme başarılı";
    header("Location:../production/sosyal-ayarlar.php?durum=ok");
  }
  else{
  //echo "güncelleme başarısız";
    header("Location:../production/sosyal-ayarlar.php?durum=no");
  }


}


if(isset($_POST['mailayarkaydet'])){
//TABLO GÜNCELLEME KODLARI
  $ayarkaydet=$db->prepare("UPDATE ayar SET

    ayar_smtphost=:ayar_smtphost,
    ayar_smtpuser=:ayar_smtpuser,
    ayar_smtppassword=:ayar_smtppassword,
    ayar_smtpport=:ayar_smtpport
    WHERE ayar_id=0");

  $update=$ayarkaydet->execute(
    ['ayar_smtphost' => $_POST['ayar_smtphost'],
    'ayar_smtpuser' => $_POST['ayar_smtpuser'],
    'ayar_smtppassword' => $_POST['ayar_smtppassword'],
    'ayar_smtpport' => $_POST['ayar_smtpport']
  ]);



  if($update){
  //echo "güncelleme başarılı";
    header("Location:../production/mail-ayarlar.php?durum=ok");
  }
  else{
  //echo "güncelleme başarısız";
    header("Location:../production/mail-ayarlar.php?durum=no");
  }


}



if(isset($_POST['hakkimizdakaydet'])){
//TABLO GÜNCELLEME KODLARI
  $ayarkaydet=$db->prepare("UPDATE hakkimizda SET

    hakkimizda_baslik=:hakkimizda_baslik,
    hakkimizda_icerik=:hakkimizda_icerik,
    hakkimizda_video=:hakkimizda_video,
    hakkimizda_vizyon=:hakkimizda_vizyon,
    hakkimizda_misyon=:hakkimizda_misyon WHERE hakkimizda_id=0");

  $update=$ayarkaydet->execute(
    ['hakkimizda_baslik' => $_POST['hakkimizda_baslik'],
    'hakkimizda_icerik' => $_POST['hakkimizda_icerik'],
    'hakkimizda_video' => $_POST['hakkimizda_video'],
    'hakkimizda_vizyon' => $_POST['hakkimizda_vizyon'],
    'hakkimizda_misyon' => $_POST['hakkimizda_misyon']]);



  if($update){
  //echo "güncelleme başarılı";
    header("Location:../production/hakkimizda.php?durum=ok");
  }
  else{
  //echo "güncelleme başarısız";
    header("Location:../production/hakkimizda.php?durum=no");
  }


}


if(isset($_POST['kategoriduzenle'])){

    $kategori_id=$_POST['kategori_id'];
    $kategori_seourl=seo($_POST['kategori_ad']);
//TABLO GÜNCELLEME KODLARI
    $ayarkaydet=$db->prepare("UPDATE kategori SET

      kategori_ad=:ad,
      kategori_sira=:sira,
      kategori_ust=:ust,
      kategori_durum=:durum,
      kategori_seourl=:seourl
      
      WHERE kategori_id={$_POST['kategori_id']}");


     $update=$ayarkaydet->execute(
      ['ad' => $_POST['kategori_ad'],
      'sira' => $_POST['kategori_sira'],
      'ust' => $_POST['kategori_ust'],
      'durum' => $_POST['kategori_durum'],
      'seourl' => $kategori_seourl
    ]);
 
    

  



    if($update){

  //echo "güncelleme başarılı";
      header("Location:../production/kategori-duzenle.php?kategori_id=$kategori_id&durum=ok");
      exit;
    }
    else{
  //echo "güncelleme başarısız";
      header("Location:../production/kategori-duzenle.php?kategori_id=$kategori_id&durum=no");
      exit;
    }


  }

  if(isset($_POST['kategoriekle'])){

    
    $kategori_seourl=seo($_POST['kategori_ad']);
//TABLO GÜNCELLEME KODLARI
    $ayarkaydet=$db->prepare("INSERT into kategori SET

      kategori_ad=:ad,
      kategori_sira=:sira,
      kategori_ust=:ust,
      kategori_durum=:durum,
      kategori_seourl=:seourl");


     $insert=$ayarkaydet->execute(
      ['ad' => $_POST['kategori_ad'],
      'sira' => $_POST['kategori_sira'],
      'ust' => $_POST['kategori_ust'],
      'durum' => $_POST['kategori_durum'],
      'seourl' => $kategori_seourl
    ]);
 
    

  



    if($insert){

  //echo "güncelleme başarılı";
      header("Location:../production/kategori.php?durum=ok");
      exit;
    }
    else{
  //echo "güncelleme başarısız";
      header("Location:../production/kategori.php?durum=no");
      exit;
    }


  }
  if (isset($_POST['urunekle'])) {

  $urun_seourl=seo($_POST['urun_keyword']);

  $kaydet=$db->prepare("INSERT INTO urun SET
    kategori_id=:kategori_id,
    urun_ad=:urun_ad,
    urun_detay=:urun_detay,
    urun_fiyat=:urun_fiyat,
    urun_video=:urun_video,
    urun_keyword=:urun_keyword,
    urun_durum=:urun_durum,
    urun_stok=:urun_stok, 
    urun_seourl=:seourl   
    ");
  $insert=$kaydet->execute(array(
    'kategori_id' => $_POST['kategori_id'],
    'urun_ad' => $_POST['urun_ad'],
    'urun_detay' => $_POST['urun_detay'],
    'urun_fiyat' => $_POST['urun_fiyat'],
    'urun_video' => $_POST['urun_video'],
    'urun_keyword' => $_POST['urun_keyword'],
    'urun_durum' => $_POST['urun_durum'],
    'urun_stok' => $_POST['urun_stok'],
    'seourl' => $urun_seourl
      
    ));

  if ($insert) {

    Header("Location:../production/urun.php?durum=ok");

  } else {

    Header("Location:../production/urun.php?durum=no");
  }

}


if (isset($_POST['urunduzenle'])) {

  $urun_id=$_POST['urun_id'];
  $urun_seourl=seo($_POST['urun_keyword']);

  $kaydet=$db->prepare("UPDATE urun SET
    kategori_id=:kategori_id,
    urun_ad=:urun_ad,
    urun_detay=:urun_detay,
    urun_fiyat=:urun_fiyat,
    urun_video=:urun_video,
    urun_onecikar=:urun_onecikar,
    urun_keyword=:urun_keyword,
    urun_durum=:urun_durum,
    urun_stok=:urun_stok, 
    urun_seourl=:seourl   
    WHERE urun_id={$_POST['urun_id']}");
  $update=$kaydet->execute(array(
    'kategori_id' => $_POST['kategori_id'],
    'urun_ad' => $_POST['urun_ad'],
    'urun_detay' => $_POST['urun_detay'],
    'urun_fiyat' => $_POST['urun_fiyat'],
    'urun_video' => $_POST['urun_video'],
    'urun_onecikar' => $_POST['urun_onecikar'],
    'urun_keyword' => $_POST['urun_keyword'],
    'urun_durum' => $_POST['urun_durum'],
    'urun_stok' => $_POST['urun_stok'],
    'seourl' => $urun_seourl
      
    ));

  if ($update) {

    Header("Location:../production/urun-duzenle.php?durum=ok&urun_id=$urun_id");

  } else {

    Header("Location:../production/urun-duzenle.php?durum=no&urun_id=$urun_id");
  }

}

if(!empty($_GET['kategorisil'])){
  if($_GET['kategorisil']=="ok"){

    islemkontrol();

  $sil=$db->prepare("DELETE from kategori where kategori_id=:id");
  $kontrol=$sil->execute(['id'=>$_GET['kategori_id']]);


  if($kontrol){
  //echo "güncelleme başarılı";
    header("Location:../production/kategori.php?sil=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
   header("Location:../production/kategori.php?sil=no");
   exit;
 }

}
}




if(!empty($_GET['kategorisil'])){
  if($_GET['kategorisil']=="ok"){

      islemkontrol();

  $sil=$db->prepare("DELETE from kategori where kategori_id=:id");
  $kontrol=$sil->execute(['id'=>$_GET['kategori_id']]);


  if($kontrol){
  //echo "güncelleme başarılı";
    header("Location:../production/kategori.php?sil=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
   header("Location:../production/kategori.php?sil=no");
   exit;
 }


}
}


if(!empty($_GET['urunsil'])){
if($_GET['urunsil']=="ok"){

    islemkontrol();

  $sil=$db->prepare("DELETE from urun where urun_id=:id");
  $kontrol=$sil->execute(['id'=>$_GET['urun_id']]);


  if($kontrol){
  //echo "güncelleme başarılı";
    header("Location:../production/urun.php?sil=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
   header("Location:../production/urun.php?sil=no");
   exit;
 }


}
}

if(!empty($_GET['onecikarmaIslemi'])){
if ($_GET['onecikarmaIslemi']=="ok") {

  $urun_id=$_GET['urun_id'];
  $urun_onecikar=$_GET['urun_onecikar'];
  if($urun_onecikar==1){
    $urun_onecikar=0;
  }
  else{
    $urun_onecikar=1;
  }

  

  $kaydet=$db->prepare("UPDATE urun SET
    urun_onecikar=:urun_onecikar
     
    WHERE urun_id={$urun_id}");
  $update=$kaydet->execute(array(
    'urun_onecikar' => $urun_onecikar,
    
      
    ));

  if ($update) {

    Header("Location:../production/urun.php?durum=ok&urun_id=$urun_id");

  } else {

    Header("Location:../production/urun.php?durum=no&urun_id=$urun_id");
  }

}
}


if (isset($_POST['yorumkaydet'])) {


  $gelen_url=$_POST['gelen_url'];

  $ayarekle=$db->prepare("INSERT INTO yorumlar SET
    yorum_detay=:yorum_detay,
    kullanici_id=:kullanici_id,
    urun_id=:urun_id
     
    
    ");

  $insert=$ayarekle->execute(array(
    'yorum_detay' => $_POST['yorum_detay'],
    'kullanici_id' => $_POST['kullanici_id'],
    'urun_id' => $_POST['urun_id']
    ));


  if ($insert) {

    Header("Location:$gelen_url?durum=ok");

  } else {

    Header("Location:$gelen_url?durum=no");
  }

}



if(!empty($_GET['yorumlarsil'])){
if($_GET['yorumlarsil']=="ok"){
    islemkontrol();
  $sil=$db->prepare("DELETE from yorumlar where yorum_id=:id");
  $kontrol=$sil->execute(['id'=>$_GET['yorum_id']]);


  if($kontrol){
  //echo "güncelleme başarılı";
    header("Location:../production/yorumlar?durum=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
   header("Location:../production/yorumlar?durum=no");
   exit;
 }


}
}



if(!empty($_GET['siparissil'])){
if($_GET['siparissil']=="ok"){
    islemkontrol();
  $sil=$db->prepare("DELETE from siparis where siparis_id=:id");
  $kontrol=$sil->execute(['id'=>$_GET['siparis_id']]);


  if($kontrol){
  //echo "güncelleme başarılı";
    header("Location:../production/siparisler?durum=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
   header("Location:../production/siparisler?durum=no");
   exit;
 }


}
}


if (isset($_POST['sepetekle'])) {

$urun_id=$_POST['urun_id'];

$urunAdetSor=$db->prepare("SELECT urun_stok FROM urun WHERE urun_id=:id");
$urunAdetSor->execute(['id'=> $urun_id]);
$urunAdetCek=$urunAdetSor->fetch(PDO::FETCH_ASSOC);


  $ayarekle=$db->prepare("INSERT INTO sepet SET
    urun_adet=:urun_adet,
    kullanici_id=:kullanici_id,
    urun_id=:urun_id  
    
    ");

  $insert=$ayarekle->execute(array(
    'urun_adet' => $_POST['urun_adet'],
    'kullanici_id' => $_POST['kullanici_id'],
    'urun_id' => $_POST['urun_id']
    
    ));


  if ($insert) {

    Header("Location:../../sepet?durum=ok");
    exit;

  } else {

    Header("Location:../../sepet?durum=no");
    exit;
  }
}




if(!empty($_GET['yorum_onayla'])){
if ($_GET['yorum_onayla']=="ok") {

  $yorum_onay= $_GET['yorum_onay'];
  if($yorum_onay==0){
    $yorum_onay=1;
  }
  else{
    $yorum_onay=0;
  }

  $duzenle=$db->prepare("UPDATE yorumlar SET
    
    yorum_onay=:yorum_onay
    
    WHERE yorum_id={$_GET['yorum_id']}");
  
  $update=$duzenle->execute(array(

    'yorum_onay' => $yorum_onay
    ));



  if ($update) {

    

    Header("Location:../production/yorumlar.php?durum=ok");

  } else {

    Header("Location:../production/yorumlar.php?durum=no");
  }

}

}


if (isset($_POST['bankaekle'])) {

  $kaydet=$db->prepare("INSERT INTO banka SET
    banka_ad=:ad,
    banka_durum=:banka_durum, 
    banka_hesapadsoyad=:banka_hesapadsoyad,
    banka_iban=:banka_iban
    ");
  $insert=$kaydet->execute(array(
    'ad' => $_POST['banka_ad'],
    'banka_durum' => $_POST['banka_durum'],
    'banka_hesapadsoyad' => $_POST['banka_hesapadsoyad'],
    'banka_iban' => $_POST['banka_iban']    
    ));

  if ($insert) {

    Header("Location:../production/banka.php?durum=ok");

  } else {

    Header("Location:../production/banka.php?durum=no");
  }

}


if (isset($_POST['bankaduzenle'])) {

  $banka_id=$_POST['banka_id'];

  $kaydet=$db->prepare("UPDATE banka SET

    banka_ad=:ad,
    banka_durum=:banka_durum, 
    banka_hesapadsoyad=:banka_hesapadsoyad,
    banka_iban=:banka_iban
    WHERE banka_id={$_POST['banka_id']}");
  $update=$kaydet->execute(array(
    'ad' => $_POST['banka_ad'],
    'banka_durum' => $_POST['banka_durum'],
    'banka_hesapadsoyad' => $_POST['banka_hesapadsoyad'],
    'banka_iban' => $_POST['banka_iban']    
    ));

  if ($update) {

    Header("Location:../production/banka-duzenle.php?banka_id=$banka_id&durum=ok");

  } else {

    Header("Location:../production/banka-duzenle.php?banka_id=$banka_id&durum=no");
  }


  

}

if(!empty($_GET['bankasil'])){
if ($_GET['bankasil']=="ok") {
    islemkontrol();
  $sil=$db->prepare("DELETE from banka where banka_id=:banka_id");
  $kontrol=$sil->execute(array(
    'banka_id' => $_GET['banka_id']
    ));

  if ($kontrol) {

    
    Header("Location:../production/banka.php?durum=ok");

  } else {

    Header("Location:../production/banka.php?durum=no");
  }

}
}

if (isset($_POST['kullanicisifreguncelle'])) {

  echo $kullanici_eskipassword=trim($_POST['kullanici_eskipassword']); echo "<br>";
  echo $kullanici_passwordone=trim($_POST['kullanici_passwordone']); echo "<br>";
  echo $kullanici_passwordtwo=trim($_POST['kullanici_passwordtwo']); echo "<br>";

  $kullanici_password=md5($kullanici_eskipassword);


  $kullanicisor=$db->prepare("select * from kullanici where kullanici_password=:password");
  $kullanicisor->execute(array(
    'password' => $kullanici_password
    ));

      //dönen satır sayısını belirtir
  $say=$kullanicisor->rowCount();



  if ($say==0) {

    header("Location:../../sifre-guncelle?durum=eskisifrehata");



  } else {



  //eski şifre doğruysa başla


    if ($kullanici_passwordone==$kullanici_passwordtwo) {


      if (strlen($kullanici_passwordone)>=6) {


        //md5 fonksiyonu şifreyi md5 şifreli hale getirir.
        $password=md5($kullanici_passwordone);

        $kullanici_yetki=1;

        $kullanicikaydet=$db->prepare("UPDATE kullanici SET
          kullanici_password=:kullanici_password
          WHERE kullanici_id={$_POST['kullanici_id']}");

        
        $insert=$kullanicikaydet->execute(array(
          'kullanici_password' => $password
          ));

        if ($insert) {


          header("Location:../../sifre-guncelle.php?durum=sifredegisti");


        //Header("Location:../production/genel-ayarlar.php?durum=ok");

        } else {


          header("Location:../../sifre-guncelle.php?durum=no");
        }





    // Bitiş



      } else {


        header("Location:../../sifre-guncelle.php?durum=eksiksifre");


      }



    } else {

      header("Location:../../sifre-guncelle?durum=sifreleruyusmuyor");

      exit;


    }


  }

  exit;

  if ($update) {

    header("Location:../../sifre-guncelle?durum=ok");

  } else {

    header("Location:../../sifre-guncelle?durum=no");
  }

}


if (isset($_POST['kullanicibilgiguncelle'])) {

  $kullanici_id=$_POST['kullanici_id'];

  $ayarkaydet=$db->prepare("UPDATE kullanici SET
    kullanici_adsoyad=:kullanici_adsoyad,
    kullanici_tc=:kullanici_tc,
    kullanici_adres=:kullanici_adres
    WHERE kullanici_id={$_POST['kullanici_id']}");

  $update=$ayarkaydet->execute(array(
    'kullanici_adsoyad' => $_POST['kullanici_adsoyad'],
    'kullanici_tc' => $_POST['kullanici_tc'],
    'kullanici_adres' => $_POST['kullanici_adres']
    ));



  if ($update) {

    Header("Location:../../hesabim?durum=ok");

  } else {

    Header("Location:../../hesabim?durum=no");
  }

}



//Sipariş İşlemleri

if (isset($_POST['bankasiparisekle'])) {

$siparis_tip="Banka Havalesi";
$kullanici_id=$_POST['kullanici_id'];

$sepetsor=$db->prepare("SELECT * FROM sepet where kullanici_id=:id");
    $sepetsor->execute(array(
      'id' => $kullanici_id
      ));

while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)) {

$urun_id=$sepetcek['urun_id'];;

$urunAdetSor=$db->prepare("SELECT urun_stok FROM urun WHERE urun_id=:id");
$urunAdetSor->execute(['id'=> $urun_id]);
$urunAdetCek=$urunAdetSor->fetch(PDO::FETCH_ASSOC);


if($urunAdetCek['urun_stok'] >= $sepetcek['urun_adet']){

$urunGuncelStok=$urunAdetCek['urun_stok'] - $sepetcek['urun_adet'];
$db->exec("SET NAMES utf8");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sonuc = $db->exec("UPDATE urun SET urun_stok =$urunGuncelStok  WHERE urun_id = $urun_id");

    if ($sonuc <= 0) {
        Header("Location:../../odeme?durum=no");
        exit;
    }
  }
  else{
$geldigi_sayfa = $_SERVER['HTTP_REFERER']; 

header("Refresh: 0; url=".$geldigi_sayfa."?durum=yetersizStok&urun_id=$urun_id");
        exit;
}
}

  $kaydet=$db->prepare("INSERT INTO siparis SET
    kullanici_id=:kullanici_id,
    siparis_tip=:siparis_tip, 
    siparis_banka=:siparis_banka,
    siparis_toplam=:siparis_toplam
    ");
  $insert=$kaydet->execute(array(
    'kullanici_id' => $_POST['kullanici_id'],
    'siparis_tip' => $siparis_tip,
    'siparis_banka' => $_POST['siparis_banka'],
    'siparis_toplam' => $_POST['siparis_toplam']    
    ));

  if ($insert) {

    //Sipariş başarılı kaydedilirse...

    echo $siparis_id = $db->lastInsertId();

    echo "<hr>";


    $kullanici_id=$_POST['kullanici_id'];
    $sepetsor=$db->prepare("SELECT * FROM sepet where kullanici_id=:id");
    $sepetsor->execute(array(
      'id' => $kullanici_id
      ));

    while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)) {

      $urun_id=$sepetcek['urun_id']; 
      $urun_adet=$sepetcek['urun_adet'];

      $urunsor=$db->prepare("SELECT * FROM urun where urun_id=:id");
      $urunsor->execute(array(
        'id' => $urun_id
        ));

      $uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);
      
      echo $urun_fiyat=$uruncek['urun_fiyat'];


      
      $kaydet=$db->prepare("INSERT INTO siparis_detay SET
        
        siparis_id=:siparis_id,
        urun_id=:urun_id, 
        urun_fiyat=:urun_fiyat,
        urun_adet=:urun_adet
        ");
      $insert=$kaydet->execute(array(
        'siparis_id' => $siparis_id,
        'urun_id' => $urun_id,
        'urun_fiyat' => $urun_fiyat,
        'urun_adet' => $urun_adet

        ));


    }

    if ($insert) {

      

      //Sipariş detay kayıtta başarıysa sepeti boşalt

      $sil=$db->prepare("DELETE from sepet where kullanici_id=:kullanici_id");
      $kontrol=$sil->execute(array(
        'kullanici_id' => $kullanici_id
        ));

      
      Header("Location:../../siparislerim?durum=ok");
      exit;


    }

    




  } else {

    echo "başarısız";

    //Header("Location:../production/siparis.php?durum=no");
  }



}


if(!empty($_GET['siparisdurumu_guncelle'])){
if ($_GET['siparisdurumu_guncelle']=="ok") {
  $siparis_durum= $_GET['siparis_durum'];
    $siparis_durum++;

  if($siparis_durum>3){
    $siparis_durum=0;
  }


  $duzenle=$db->prepare("UPDATE siparis SET
    
    siparis_durum=:siparis_durum
    
    WHERE siparis_id={$_GET['siparis_id']}");
  
  $update=$duzenle->execute(array(

    'siparis_durum' => $siparis_durum
    ));



  if ($update) {

    

    Header("Location:../production/siparisler.php?durum=ok");

  } else {

    Header("Location:../production/siparisler.php?durum=no");
  }

}

}


if(isset($_POST['urunfotosil'])) {
  islemkontrol();
  $urun_id=$_POST['urun_id'];


  echo $checklist = $_POST['urunfotosec'];

  
  foreach($checklist as $list) {

    $sil=$db->prepare("DELETE from urunfoto where urunfoto_id=:urunfoto_id");
    $kontrol=$sil->execute(array(
      'urunfoto_id' => $list
      ));
  }

  if ($kontrol) {

    Header("Location:../production/urun-galeri.php?urun_id=$urun_id&durum=ok");

  } else {

    Header("Location:../production/urun-galeri.php?urun_id=$urun_id&durum=no");
  }


} 



if(!empty($_GET['sepettenUrunusil'])){
if($_GET['sepettenUrunusil']=="ok"){

    islemkontrol();
  $sil=$db->prepare("DELETE FROM sepet WHERE urun_id=:urun_id AND kullanici_id=:kullanici_id");
  $kontrol=$sil->execute(['urun_id'=>$_GET['urun_id'], 'kullanici_id' => $_GET['kullanici_id']]);


  if($kontrol){
  //echo "güncelleme başarılı";
    header("Location:../../sepet?durum=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
   header("Location:../../sepet?durum=no");
   exit;
 }


}
}


if(!empty($_GET['UrunuSil2'])){
if($_GET['UrunuSil2']=="ok"){
echo "mrha";
exit ;
    islemkontrol();
  $sil=$db->prepare("DELETE FROM sepet WHERE urun_id=:urun_id AND kullanici_id=:kullanici_id");
  $kontrol=$sil->execute(['urun_id'=>$_GET['urun_id'], 'kullanici_id' => $_GET['kullanici_id']]);


  if($kontrol){
  //echo "güncelleme başarılı";
    header("Location:../../odeme?durum=ok");
    exit;
  }
  else{
  //echo "güncelleme başarısız";
   header("Location:../../odeme?durum=no");
   exit;
 }


}
}





?>