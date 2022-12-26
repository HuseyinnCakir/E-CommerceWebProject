<?php 

include 'header.php'; 

//Belirli veriyi seçme işlemi
$siparislersor=$db->prepare("SELECT siparis_id,siparis_zaman,kullanici_id,siparis_toplam,siparis_tip,siparis_durum FROM siparis order by siparis_zaman ASC"); // limit ile butun siparişler değilde zamana göre 10 tanesi getirebilir. çok sipariş olması durumunda sistem cok yavaşlıyabilir.
$siparislersor->execute();


?>


<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Siparişler Listesi <small>,

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
                    <th>Sipariş No</th>
                    <th>Sipariş Zaman</th>
                    <th>Kullanıcı Ad</th>
                    <th>Toplam Fiyat</th>
                    <th>Ödeme Tipi</th>
                    <th>Durum</th>
                    <th>Sipariş Detayı</th>
                    <th>Sil</th>
                    
                  </tr>
                </thead>

                <tbody>

                  <?php 
                  
                  while($siparislercek=$siparislersor->fetch(PDO::FETCH_ASSOC)) { ?>


                    <tr>
                      
                      <td><?php echo $siparislercek['siparis_id'] ?></td>
                      <td> <?php echo $siparislercek['siparis_zaman'] ?></td>
                      <td><?php 
                      $kullanici_id=$siparislercek['kullanici_id'];
                      $kullanicisor=$db->prepare("SELECT * FROM kullanici where kullanici_id=:id");
                      $kullanicisor->execute(['id'=> $kullanici_id]);
                      $kullanicicek=$kullanicisor->fetch(PDO::FETCH_ASSOC);
                      if(isset($kullanicicek)){
                      echo $kullanicicek['kullanici_adsoyad']; }
                     ?></td>
                     

                      <td><?php echo $siparislercek['siparis_toplam']
                       ?></td>
                        <td><?php echo $siparislercek['siparis_tip']
                       ?></td>
                       <td><center><a href="../netting/islem.php?siparis_id=<?php echo $siparislercek['siparis_id']; ?>&siparisdurumu_guncelle=ok&siparis_durum=<?php echo $siparislercek['siparis_durum']; ?>"> 
                        <?php if($siparislercek['siparis_durum']==0){?>
                        <button class="btn btn-danger btn-xs">Sipariş Alındı</button></a></center></td>

                          <?php }else if($siparislercek['siparis_durum']==1){ ?>

                      <button  class="btn btn-warning btn-xs">Sipariş Hazırlanıyor</button></a></center></td>
                      <?php   } 
                      else if($siparislercek['siparis_durum']==2){ ?>

                      <button  class="btn btn-warning btn-xs">Kargoya Verildi</button></a></center></td>
                      <?php   }else if($siparislercek['siparis_durum']==3){ ?>

                      <button  class="btn btn-success btn-xs">Teslim Edildi</button></a></center></td>
                      <?php   }  ?>

                      <td><center><a href="siparis-detay.php?siparis_id=<?php echo $siparislercek['siparis_id']; ?>"><button class="btn btn-primary btn-xs">Detay</button></a></center></td>
                      <td><center><a href="../netting/islem.php?siparis_id=<?php echo $siparislercek['siparis_id'];  ?>&siparissil=ok"><button class="btn btn-danger btn-xs">Sil</button></center></td>
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
