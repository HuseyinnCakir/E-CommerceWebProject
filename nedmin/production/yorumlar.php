<?php 

include 'header.php'; 

//Belirli veriyi seçme işlemi
$yorumlarsor=$db->prepare("SELECT yorum_id,kullanici_id,urun_id,yorum_detay,yorum_onay FROM yorumlar order by yorum_onay ASC");
$yorumlarsor->execute();


?>


<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Yorumlar Listesi <small>,

              <?php 
              if(!empty($_GET['durum'])){
                if ($_GET['durum']=="ok") {?>

                  <b style="color:green;">İşlem Başarılı...</b>

                <?php } elseif ($_GET['durum']=="no") {?>

                  <b style="color:red;">İşlem Başarısız...</b>

                <?php } }

                ?>


              </small></h2>
              
             

              <!-- Div İçerik Başlangıç -->

              <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Sıra No</th>
                   <th>Yorum İçeriği</th>
                    <th>Kullanıcı Ad</th>
                    <th>Ürün Ad</th>
                    <th>Durum</th>
                    <th></th>
                  </tr>
                </thead>

                <tbody>

                  <?php 
                  $siraNo=0;
                  while($yorumlarcek=$yorumlarsor->fetch(PDO::FETCH_ASSOC)) { $siraNo++?>


                    <tr>
                      <td width="20"><?php echo $siraNo ?></td>
                      <td><?php echo $yorumlarcek['yorum_detay'] ?></td>
                      <td><?php 
                      $kullanici_id=$yorumlarcek['kullanici_id'];
                      $kullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_id=:id");
                      $kullanicisor->execute(['id'=> $kullanici_id]);
                      $kullanicicek=$kullanicisor->fetch(PDO::FETCH_ASSOC);
                      echo $kullanicicek['kullanici_adsoyad'];
                     ?></td>
                      
                      <td><?php 
                        $urun_id=$yorumlarcek['urun_id'];
                        $urunsor=$db->prepare("SELECT urun_ad FROM urun WHERE urun_id=:id");
                        $urunsor->execute(['id' => $urun_id]);
                        $uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);
                         echo $uruncek['urun_ad'];
                       ?></td>
                       <td><center><a href="../netting/islem.php?yorum_id=<?php echo $yorumlarcek['yorum_id']; ?>&yorum_onayla=ok&yorum_onay=<?php echo $yorumlarcek['yorum_onay']; ?>"> 
                        <?php if($yorumlarcek['yorum_onay']==1){?>
                        <button class="btn btn-warning btn-xs">Kaldır</button></a></center></td>

                          <?php }else{ ?>

                      <button  class="btn btn-success btn-xs">Onayla</button></a></center></td>
                      <?php   } ?>


                      
                      
                      <td><center><a href="../netting/islem.php?yorum_id=<?php echo $yorumlarcek['yorum_id'];  ?>&yorumlarsil=ok"><button class="btn btn-danger btn-xs">Sil</button></center></td>
                      </tr>



                    <?php  }

                    ?>


                  </tbody>
                </table>

                <!-- Div İçerik Bitişi -->


              </div>
            </div>
          </div>
        </div>




      </div>
    </div>
    <!-- /page content -->

    <?php include 'footer.php'; ?>
