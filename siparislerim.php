<?php include 'header.php'; 
if(empty($_SESSION['userkullanici_mail'])){
  Header("Location:index.php?durum=izinsiz");
}
?>

<head>	
<title>Siparişlerim</title></head>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-title-wrap">
				<div class="page-title-inner">
					<div class="row">
						<div class="col-md-12">
							<div class="bigtitle">Sipariş Bilgilerim</div>
							<p >Vermiş olduğunuz siparişlerinizi listeliyorsunuz</p>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>

	
		<div class="row">
			<div class="col-md-12">
				<div class="title-bg">
					<div class="title">Sipariş Bilgileri</div>
				</div>

				<div class="table-responsive">
					<table class="table table-bordered chart">
						<thead>
							<tr>
								
								<th>Sipariş No</th>
								<th>Tarih</th>
								<th>Tutar</th>
								<th>Ödeme Tip</th>
								<th>Durum</th>
								<th></th>
								
							</tr>
						</thead>
						<tbody>

							<?php 
							 $kullanici_id=$kullanicicek['kullanici_id'];
							$siparissor=$db->prepare("SELECT * FROM siparis where kullanici_id=:id");
							$siparissor->execute(array(
								'id' => $kullanici_id
								));

								while($sipariscek=$siparissor->fetch(PDO::FETCH_ASSOC)) {?>
								<tr>

									<td><?php echo $sipariscek['siparis_id']; ?></td>
									<td><?php echo $sipariscek['siparis_zaman']; ?></td>
									<td><?php echo $sipariscek['siparis_toplam']; ?></td>
									<td><?php echo $sipariscek['siparis_tip']; ?></td>
									<td><?php if($sipariscek['siparis_durum'] ==0){
										echo "Siparişiniz Alındı.";} else if($sipariscek['siparis_durum']==1) { echo "Siparişiniz Hazırlanıyor.";}
										else if($sipariscek['siparis_durum']==2) { echo "Siparişiniz Kargoya Verildi.";}
										else if($sipariscek['siparis_durum']==3) { echo "Siparişiniz Teslim edildi.";}
									  ?></td>

									<td><a href="siparis-detay?siparis_id=<?php echo $sipariscek['siparis_id']; ?>"><button class="btn btn-primary btn-xs">Detay</button></a></td>
								</tr>

								<?php } ?>

							</tbody>
						</table>
					</div>

				</div>

			</div>
		</div>
	
	<div class="spacer"></div>
</div>

<?php include 'footer.php'; ?>