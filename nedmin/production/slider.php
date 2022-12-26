<?php 

include 'header.php'; 

//Belirli veriyi seçme işlemi
$slidersor=$db->prepare("SELECT slider_id,slider_ad,slider_resimyol,slider_link,slider_sira,slider_durum FROM slider ORDER BY slider_sira ASC");
$slidersor->execute();


?>


<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Slider Listesi <small>,

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
                <a href="slider-ekle.php"><button class="btn btn-success btn-xs">Yeni Ekle</button></a>
              </div>
              
            </div>
            <div class="x_content">


              <!-- Div İçerik Başlangıç -->

              <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Sıra No</th>
                    <th>Ad</th>
                    <th>Resim</th>
                    <th>Link</th>
                    <th>Sıra</th>
                    <th>Durum</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>

                <tbody>

                  <?php 
                  $siraNo=0;
                  while($slidercek=$slidersor->fetch(PDO::FETCH_ASSOC)) { $siraNo++?>


                    <tr>
                      <td width="20"><?php echo $siraNo ?></td>
                      <td><?php echo $slidercek['slider_ad'] ?></td>
                      <td><img width="200" src="../../<?php echo $slidercek['slider_resimyol'] ?>"></td>
                      <td><?php echo $slidercek['slider_link'] ?></td>
                      <td><?php echo $slidercek['slider_sira'] ?></td>
                      <td><center><?php    


                      if($slidercek['slider_durum']==1){?>
                        <button class="btn btn-success btn-xs">Aktif</button>
                      <?php  }
                      else{ ?>
                        <button class="btn btn-danger btn-xs">Pasif</button>
                      <?php   } ?>

                      
                      <td><center><a href="slider-duzenle.php?slider_id=<?php echo $slidercek['slider_id']; ?>"><button class="btn btn-primary btn-xs">Düzenle</button></a></center></td>
                      <td><center><a href="../netting/islem.php?slider_id=<?php echo $slidercek['slider_id'];  ?>&slidersil=ok&slider_resimyol=<?php echo $slidercek['slider_resimyol']; ?>"><button class="btn btn-danger btn-xs">Sil</button></a></center></td>
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
