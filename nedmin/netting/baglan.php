<?php  

try{

  
  $db=new PDO("mysql:host=localhost;dbname=eticaret;charset=utf8",'root','');
  //echo "veritabanı bağlantısı basarili";
} catch(PDOException $e){

  echo $e->getMessage();
}


?>
