<?php include 'header.php' ;


if(empty($_SESSION['userkullanici_mail'])){
  Header("Location:index.php?durum=izinsiz");
}
?>


<head>	
<title>Ödeme Sayfası</title></head>
<div class="container">

	<div class="clearfix"></div>
	<div class="lines"></div>
</div>

<div class="container">
	<div class="row">
			<?php 
              if(!empty($_GET['durum'])){
                if ($_GET['durum']=="yetersizStok") {?>

                  <b style="color:red;"><?php echo $_GET['urun_id']; ?> ürün kodlu ürünün stoğu bittiği için sipariş BAŞARISIZ oldu !!</b>
                  <br>
                  <u></ul><a href="sepet"><b style="color:green;"><?php echo $_GET['urun_id']; ?> ürün kodlu ürünü sepet sayfasından silip siparişe devam edebilirsiniz.</b></a></u>
                 <?php  } }

            ?>
		<div class="col-md-12">
		
			
		</div>
	</div>
	<div class="title-bg">
		<div class="title">Ödeme Sayfası</div>
	</div>

	<div class="table-responsive">
		<table class="table table-bordered chart">
			<thead>
				<tr>
					<th>Ürün Resim</th>
					<th>Ürün ad</th>
					<th>Ürün Kodu</th>
					<th>Adet</th>
					<th>Toplam Fiyat</th>
					
				</tr>
			</thead>
		<form action="nedmin/netting/islem.php" method="POST">
			<tbody>


				<?php 
				
				$kullanici_id=$kullanicicek['kullanici_id'];
				$sepetsor=$db->prepare("SELECT * FROM sepet where kullanici_id=:id");
				$sepetsor->execute(array(
					'id' => $kullanici_id
					));

				while($sepetcek=$sepetsor->fetch(PDO::FETCH_ASSOC)) {

					$urun_id=$sepetcek['urun_id'];
					$urunsor=$db->prepare("SELECT * FROM urun where urun_id=:urun_id");
					$urunsor->execute(array(
						'urun_id' => $urun_id
						));

					$uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);
					$urunfotosor=$db->prepare("SELECT urunfoto_resimyol FROM urunfoto WHERE urun_id=:urun_id ORDER BY urunfoto_sira ASC LIMIT 1");
        $urunfotosor->execute(['urun_id'=> $urun_id]);
        $urunfotocek=$urunfotosor->fetch(PDO::FETCH_ASSOC);
					?>

					<tr>
						<td><img src="<?php if(isset($urunfotocek["urunfoto_resimyol"])) echo $urunfotocek["urunfoto_resimyol"]; else{ echo "dimg/logo-yok.png"; } ?>"  alt="" width=100 ></td>
						<td><?php echo $uruncek['urun_ad'] ?></td>
						<td><?php echo $uruncek['urun_id'] ?></td>
						<td><?php echo $sepetcek['urun_adet'] ?></td>
						<td><?php echo $uruncek['urun_fiyat'] ?></td>
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
					<div class="total">Toplam Fiyat : <span class="bigprice"><?php echo $toplam_fiyat ?> TL</span></div>
					<div class="clearfix"></div>
					<!-- <a href="" class="btn btn-default btn-yellow">Ödeme Sayfası</a> -->
				</div>
				<div class="clearfix"></div>
			</div>
		</div>

		<div class="tab-review">
			<ul id="myTab" class="nav nav-tabs shop-tab">

				<li class="active"><a href="#desc" data-toggle="tab">Kredi Kartı</a></li>
				<li><a href="#rev" data-toggle="tab">Banka Havalesi </a></li>
			</ul>




			<div id="myTabContent" class="tab-content shop-tab-ct">
				

				<div class="tab-pane fade active in" id="desc">
					<p>
						Entegrasyon Tamamlanmadı.
					</p>
				</div>


				<div class="tab-pane fade " id="rev">

					

						<p>Ödeme yapacağınız hesap numarasını seçerek işlemi tamamlayınız.</p>


						<?php 

						$bankasor=$db->prepare("SELECT * FROM banka order by banka_id ASC");
						$bankasor->execute();

						while($bankacek=$bankasor->fetch(PDO::FETCH_ASSOC)) { ?>

						
						<input type="radio"  required="" name="siparis_banka" value="<?php echo $bankacek['banka_ad'] ?>">
						<?php echo $bankacek['banka_ad']; echo " ";echo $bankacek['banka_iban']; ?><br>


						

						<?php } ?>
						<input type="hidden" name="kullanici_id" value="<?php echo $kullanicicek['kullanici_id'] ?>">
						<input type="hidden" name="siparis_toplam" value="<?php echo $toplam_fiyat ?>">
						<hr>
						<button class="btn btn-success" type="submit" name="bankasiparisekle">Sipariş Ver</button>

					</form>

				</div>


			</div>
		</div>
		<div class="spacer"></div>
	</div>

	<?php include 'footer.php' ?>