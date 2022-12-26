<?php include 'header.php';
if(empty($_SESSION['userkullanici_mail'])){
  Header("Location:index.php?durum=izinsiz");
  exit;
}
?>

<head>	
<title>Sepet</title></head>

<div class="container">

	<div class="clearfix"></div>
	<div class="lines"></div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?php 
              if(!empty($_GET['durum'])){
                if ($_GET['durum']=="ok") {?>

                  <b style="color:green;">İşlem Başarılı...</b>

                <?php } elseif ($_GET['durum']=="no") {?>

                  <b style="color:red;">İşlem Başarısız...</b>

                  <?php }  else if ($_GET['durum']=="yetersizStok") {?>

                  <b style="color:red;">Yetersiz Stok!</b>
                <?php } }

                ?>
		</div>
	</div>
	<div class="title-bg">
		<div class="title">Alışveriş Sepetim</div>
		
	</div>

	<div class="table-responsive">
		<table class="table table-bordered chart">
			<thead>
				<tr>
					
					<th>Ürün Resim</th>
					<th>Ürün ad</th>
					<th>Adet</th>
					<th>Toplam Fiyat</th>
					<th>Ürünü Sil</th>
				</tr>
			</thead>
			<tbody>


				<?php 
				
				$kullanici_id=$kullanicicek['kullanici_id'];
				$sepetsor=$db->prepare("SELECT urun_id,SUM(urun_adet)AS urun_adet FROM sepet WHERE kullanici_id=:id GROUP BY urun_id");
				$sepetsor->execute(array(
					'id' => $kullanici_id
					));
	
				while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)){
					$urunToplamfiyat=0;
					$urun_id=$sepetcek['urun_id'];
					$urunsor=$db->prepare("SELECT urun_ad,urun_id,urun_fiyat FROM urun WHERE urun_id=:urun_id");
					$urunsor->execute(array(
						'urun_id' => $urun_id
						));
				 	
				$uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);
				$urunToplamfiyat=$uruncek['urun_fiyat']*$sepetcek['urun_adet'];
				$urunfotosor=$db->prepare("SELECT urunfoto_resimyol FROM urunfoto WHERE urun_id=:urun_id ORDER BY urunfoto_sira ASC LIMIT 1");
        $urunfotosor->execute(['urun_id'=> $urun_id]);
        $urunfotocek=$urunfotosor->fetch(PDO::FETCH_ASSOC);
					?>

					<tr>
					
						<td><img src="<?php if(isset($urunfotocek["urunfoto_resimyol"])) echo $urunfotocek["urunfoto_resimyol"]; else{ echo "dimg/logo-yok.png"; } ?>"  alt="" width=100 ></td>
						<td><?php echo $uruncek['urun_ad'] ?></td>
						
						<td><?php echo $sepetcek['urun_adet'] ?></td>
						<td><?php echo $urunToplamfiyat; ?></td>
						<td><center><a href="nedmin/netting/islem.php?kullanici_id=<?php echo $kullanici_id;?>&urun_id=<?php echo $urun_id; ?>&sepettenUrunusil=ok"><button class="btn btn-danger btn-xs">Sil</button></a></center></td>
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
					<div class="total">Toplam Fiyat : <span class="bigprice"><?php echo $toplam_fiyat; ?> TL</span></div>
					<div class="clearfix"></div>
					<a href="odeme" class="btn btn-default btn-yellow">Ödeme Sayfası</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="spacer"></div>
	</div>

	<?php include 'footer.php' ?>