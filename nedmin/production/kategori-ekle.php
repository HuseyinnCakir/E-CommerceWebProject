<?php 
include 'header.php'; 


?>


<!-- page content -->
<div class="right_col" role="main">
  <div class="">
   
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>kategori Ayarları <small>
              <?php 
              if(!empty($_GET['durum'])){
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
                  
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <br />
              <form action="../netting/islem.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                
                

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kategori Ad <span class="required">*</span>
                  </label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="first-name"  name="kategori_ad" placeholder="Kategori Adını Giriniz" required="required" class="form-control col-md-7 col-xs-12">
                  </div>
                </div>
                

                
              

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kategori Sıra <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name"  name="kategori_sira" placeholder="Kategori Sırasını Giriniz" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>


              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kategori Üst <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="first-name"  name="kategori_ust" placeholder="Hangi kategorinin alt kategorisi olsun(ID'sine göre)? (0=Üst kategori olur)" required="required" class="form-control col-md-7 col-xs-12">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Kategori Durum<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select id="heard" class="form-control" name="kategori_durum" required>

                   <option value="1"> Aktif</option>
                   <option value="0"> Pasif</option>
                 </select>
               </div>
             </div>
             

             <div class="ln_solid"></div>
             <div class="form-group">
              <div align="right" class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                
                <button type="submit"  name="kategoriekle" class="btn btn-success">Kaydet</button>
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

