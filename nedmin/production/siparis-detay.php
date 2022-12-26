<?php 

include 'header.php'; 


$toplamfiyat=0;
        $siparis_id=$_GET['siparis_id'];
        $siparissor=$db->prepare("SELECT * FROM siparis_detay where siparis_id=:id");
        $siparissor->execute(array(
          'id' => $siparis_id
          ));
?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Sipariş Detayları <small>,

              <?php 
              if(!empty($_GET['durum'])){
              if ($_GET['durum']=="ok") {?>

              <b style="color:green;">İşlem Başarılı...</b>

              <?php } elseif ($_GET['durum']=="no") {?>

              <b style="color:red;">İşlem Başarısız...</b>

              <?php }}

              ?>


            </small></h2>
           
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />

         
              <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                   
                    <th><center>Ürün Ad</center></th>
                    <th><center>Ürün Kodu</center></th>
                    <th><center>Adet</center></th>
                    <th><center>Birim Fiyat</center></th>
                    
                    
                  </tr>
                </thead>

              

                  <tbody>


        <?php 
        
        while($sipariscek=$siparissor->fetch(PDO::FETCH_ASSOC)) {

          $urun_id=$sipariscek['urun_id'];
          $urunsor=$db->prepare("SELECT * FROM urun where urun_id=:urun_id");
          $urunsor->execute(array(
            'urun_id' => $urun_id
            ));

          $uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);
          $toplamfiyat+=$uruncek['urun_fiyat']*$sipariscek['urun_adet'];

      
          ?>

          <tr>
            
            <td><center><?php echo $uruncek['urun_ad'] ?></center></td>
            <td><center><?php echo $uruncek['urun_id'] ?></center></td>
            <td><center><?php echo $sipariscek['urun_adet'] ?></center></td>
            <td><center><?php echo $uruncek['urun_fiyat'] ?></center></td>
          </tr>
          <?php } ?>

        </tbody>
      </table>
   </div>

   <div class="row">
      <div class="col-md-6">


      </div>
      <div class="col-md-3 col-md-offset-3">
        <div class="subtotal-wrap">
          <!--<div class="subtotal">
            <<p>Toplam Fiyat : $26.00</p>
            <p>Vat 17% : $54.00</p>
          </div>-->
          <div class="total">Toplam Fiyat : <span style="font-weight: bold;" class="bigprice"><?php echo $toplamfiyat ?> TL</span></div>
          <div class="clearfix"></div>
          <!-- <a href="" class="btn btn-default btn-yellow">Ödeme Sayfası</a> -->
        </div>
        <div class="clearfix"></div>
      </div>
    </div>

            



        </div>
      </div>
    </div>
  </div>




</div>
</div>
<!-- /page content -->

<?php include 'footer.php'; ?>
