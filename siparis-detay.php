<?php include 'header.php' ?>

<head>	
<title>Sipariş Detayı</title></head>
<div class="container">

	<div class="clearfix"></div>
	<div class="lines"></div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			
		</div>
	</div>
	<div class="title-bg">
		<div class="title">Sipariş Detay Sayfası</div>
	</div>

	<div class="table-responsive">
		<table class="table table-bordered chart">
			<thead>
				<tr>
					<th>Ürün Resim</th>
					<th>Ürün Ad</th>
					<th>Ürün Kodu</th>
					<th>Adet</th>
					<th>Toplam Fiyat</th>
				</tr>
			</thead>
		
			<tbody>


				<?php 
				$toplamfiyat=0;
				$siparis_id=$_GET['siparis_id'];
				$siparissor=$db->prepare("SELECT * FROM siparis_detay where siparis_id=:id");
				$siparissor->execute(array(
					'id' => $siparis_id
					));

				while($sipariscek=$siparissor->fetch(PDO::FETCH_ASSOC)) {
					$urunToplamfiyat=0;
					$urun_id=$sipariscek['urun_id'];
					$urunsor=$db->prepare("SELECT * FROM urun where urun_id=:urun_id");
					$urunsor->execute(array(
						'urun_id' => $urun_id
						));

					$uruncek=$urunsor->fetch(PDO::FETCH_ASSOC);
					$urunToplamfiyat=$uruncek['urun_fiyat']*$sipariscek['urun_adet'];
					$toplamfiyat+=$uruncek['urun_fiyat']*$sipariscek['urun_adet'];


					$urunfotosor=$db->prepare("SELECT urunfoto_resimyol FROM urunfoto WHERE urun_id=:urun_id ORDER BY urunfoto_sira ASC LIMIT 1");
        			$urunfotosor->execute(['urun_id'=> $urun_id]);
        			$urunfotocek=$urunfotosor->fetch(PDO::FETCH_ASSOC);
					?>

					<tr>
						<td><img src="<?php if(isset($urunfotocek["urunfoto_resimyol"])) echo $urunfotocek["urunfoto_resimyol"]; else{ echo "dimg/logo-yok.png"; } ?>"  alt="" width=100 ></td>
						<td><?php echo $uruncek['urun_ad'] ?></td>
						<td><?php echo $uruncek['urun_id'] ?></td>
						<td><?php echo $sipariscek['urun_adet'] ?></td>
						<td><?php echo $urunToplamfiyat; ?>TL</td>
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
					<div class="total">Sipariş Toplam Fiyat: <span class="bigprice"><?php echo $toplamfiyat?>TL</span></div>
					<div class="clearfix"></div>
					<!-- <a href="" class="btn btn-default btn-yellow">Ödeme Sayfası</a> -->
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
				</div>
			</div>
		</div>
		<div class="spacer"></div>
	</div>

	<?php include 'footer.php' ?>