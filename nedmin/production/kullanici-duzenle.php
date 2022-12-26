<?php include 'header.php'; 
if(!empty($_GET['kullanici_id'])){
  $kullanicisor=$db->prepare("SELECT kullanici_id,kullanici_resim,kullanici_zaman,kullanici_tc,kullanici_adsoyad,kullanici_mail,kullanici_durum FROM kullanici where kullanici_id=:id");
  $kullanicisor->execute(['id'=> $_GET['kullanici_id']]);
  $kullanicicek=$kullanicisor->fetch(PDO::FETCH_ASSOC);
}

?>


<!-- page content -->
<div class="right_col" role="main">
  <div class="">
   
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Kullanıcı Ayarları <small>
              <?php 
              if(isset($_GET['durum'])){
                if($_GET['durum']=="ok"){ ?>
                  <b style="color:green;">İşlem Başarılı</b>

                <?php  } 
                else if($_GET['durum']=="no"){ ?>
                  <b style="color:red;">İşlem Başarısız</b>

                <?php  }} ?>



              </small></h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Settings 1</a>
                    </li>
                    <li><a href="#">Settings 2</a>
                    </li>
                  </ul>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <br />

              <form action="../netting/islem.php" method="POST" enctype="multipart/form-data"  data-parsley-validate class="form-horizontal form-label-left">

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kullanici Profil Fotoğrafı<br><span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">

                    <?php 
                    if (strlen($kullanicicek['kullanici_resim'])>0) {?>

                      <img width="200"  src="../../<?php echo $kullanicicek['kullanici_resim']; ?>">

                    <?php } else {?>


                      <img width="200"   src="../../dimg/logo-yok.png">


                    <?php } ?>

                    
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Resim Seç<span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" id="first-name"  name="kullanici_resim"  class="form-control col-md-7 col-xs-12">
                  </div>
                </div>

                <input type="hidden" name="eski_yol" value="<?php echo $kullanicicek['kullanici_resim']; ?>">
                <input type="hidden" name="kullanici_id" value="<?php echo $kullanicicek['kullanici_id']; ?>">

                <div align="right" class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" name="kullaniciprofilfotosuduzenle" class="btn btn-primary">Güncelle</button>
                </div>

              </form>





              <form action="../netting/islem.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                <?php $zaman=explode(" ",$kullanicicek['kullanici_zaman']);


                ?>



                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kayıt Tarihi <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="date" id="first-name"  name="kullanici_zaman" disabled="" value="<?php echo $zaman[0];?>" required="required" class="form-control col-md-7 col-xs-12">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kayıt Saati <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="time" id="first-name"  name="kullanici_zaman" disabled="" value="<?php echo $zaman[1];?>" required="required" class="form-control col-md-7 col-xs-12">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Tc <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="first-name"  name="kullanici_tc" value="<?php echo $kullanicicek['kullanici_tc']; ?>" required="required" class="form-control col-md-7 col-xs-12">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Ad Soyad<span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="first-name" name="kullanici_adsoyad" value="<?php echo $kullanicicek['kullanici_adsoyad']; ?>" required="required" class="form-control col-md-7 col-xs-12">
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Mail<span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="first-name" name="kullanici_mail" disabled="" value="<?php echo $kullanicicek['kullanici_mail']; ?>" required="required" class="form-control col-md-7 col-xs-12">
                  </div>
                </div>


                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Durum<span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="heard" class="form-control" name="kullanici_durum" required>

                     <option value="1" <?php echo $kullanicicek['kullanici_durum'] == '1' ? 'selected=""' : ''; ?>> Aktif</option>
                     <option value="0" <?php echo $kullanicicek['kullanici_durum'] == '0' ? 'selected=""' : ''; ?>> Pasif</option>
                   </select>
                 </div>
               </div>
               <input type="hidden" name="kullanici_id" value=<?php echo $kullanicicek['kullanici_id']; ?>

               <div class="ln_solid"></div>
               <div class="form-group">
                <div align="right" class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  
                  <button type="submit"  name="adminduzenle" class="btn btn-success">Güncelle</button>
                </div>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

    

  </div>
</div>  

<?php include 'footer.php'; ?>

