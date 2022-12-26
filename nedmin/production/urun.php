<?php 

include 'header.php'; 

//Belirli veriyi seçme işlemi
$urunsor=$db->prepare("SELECT urun_id,urun_ad,urun_stok,urun_fiyat,urun_durum,urun_seourl,urun_keyword,urun_onecikar FROM urun ORDER BY urun_id DESC");
$urunsor->execute();


?>


<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Ürün Listesi <small>,

              <?php 
              if(!empty($_GET['durum'])){
                if ($_GET['durum']=="ok") {?>

                  <b style="color:green;">İşlem Başarılı...</b>

                <?php } elseif ($_GET['durum']=="no") {?>

                  <b style="color:red;">İşlem Başarısız...</b>

                <?php } }

                ?>


              </small></h2>
              
              <div class="clearfix"></div>
              <div align="right">
                <a href="urun-ekle.php"><button class="btn btn-success btn-xs">Yeni Ekle</button></a>
              </div>
              
            </div>
            <div class="x_content">


              <!-- Div İçerik Başlangıç -->

              <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Sıra No</th>
                    <th>Ürün Ad</th>
                    <th>Stok Miktarı</th>
                    <th>Ürün Fiyat</th>
                    <th>Resim İşlemleri</th>
                    <th>Ürün Durum</th>
                    <th>Ürün Seourl</th>
                    <th>Ürün Keyword</th>
                    <th>Ürün Öne Çıkar</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>

                <tbody>

                  <?php 
                  $siraNo=0;
                  while($uruncek=$urunsor->fetch(PDO::FETCH_ASSOC)) { $siraNo++?>


                    <tr>
                      <td width="20"><?php echo $siraNo ?></td>
                      <td><?php echo $uruncek['urun_ad'] ?></td>
                      <td><?php echo $uruncek['urun_stok'] ?></td>
                      <td><?php echo $uruncek['urun_fiyat'] ?></td>
                      <td><center><a href="urun-galeri?urun_id=<?php echo $uruncek['urun_id'] ?>"><button class="btn btn-success btn-xs">Resim İşlemleri</button></a></center></td>
                      <td><center><?php    if($uruncek['urun_durum']==1){?>
                        <button class="btn btn-success btn-xs">Aktif</button>
                      <?php  }
                      else{ ?>
                        <button class="btn btn-danger btn-xs">Pasif</button>
                      <?php   } ?>

                      <td><?php echo $uruncek['urun_seourl'] ?></td>
                      <td><?php echo $uruncek['urun_keyword'] ?></td>

                     <!-- onclick="butonfonksiyonu($uruncek['urun_id'],$uruncek['urun_onecikar'])" -->
                      <td><center><a href="../netting/islem.php?urun_id=<?php echo $uruncek['urun_id']; ?>&onecikarmaIslemi=ok&urun_onecikar=<?php echo $uruncek['urun_onecikar']; ?>"> 
                        <?php if($uruncek['urun_onecikar']==1){?>
                        <button class="btn btn-success btn-xs">EVET</button></a></center></td>

                          <?php }else{ ?>

                      <button  class="btn btn-danger btn-xs">HAYIR</button></a></center></td>
                      <?php   } ?>


                      
                      <td><center><a href="urun-duzenle.php?urun_id=<?php echo $uruncek['urun_id']; ?>"><button class="btn btn-primary btn-xs">Düzenle</button></a></center></td>
                      <td><center><a href="../netting/islem.php?urun_id=<?php echo $uruncek['urun_id'];  ?>&urunsil=ok"><button class="btn btn-danger btn-xs">Sil</button></a></center></td>
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



    