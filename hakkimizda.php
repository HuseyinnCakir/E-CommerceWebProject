﻿<?php include 'header.php';
//BELİRLİ VERİYİ SEÇME İŞLEMİ
$hakkimizdasor=$db->prepare("SELECT * FROM hakkimizda where hakkimizda_id=:id");
$hakkimizdasor->execute(['id'=> 0]);
$hakkimizdacek=$hakkimizdasor->fetch(PDO::FETCH_ASSOC);

?>
<head>	
<title>Hakkımızda</title></head>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="page-title-wrap">
				<div class="page-title-inner">
					<div class="row">
						<div class="col-md-4">
							
							<div class="bigtitle">Hakkımızda Sayfası</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-9"><!--Main content-->
			<div class="title-bg">
				<div class="title">Tanıtım Videosu</div>
			</div>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $hakkimizdacek['hakkimizda_video']; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			<div class="title-bg">
				<div class="title">Misyon</div>
			</div>

			<blockquote><?php echo $hakkimizdacek['hakkimizda_misyon']; ?> </blockquote>

			<div class="title-bg">
				<div class="title">Vizyon</div>
			</div>

			<blockquote><?php echo $hakkimizdacek['hakkimizda_vizyon']; ?> </blockquote>

			<div class="title-bg">
				<div class="title"><?php echo $hakkimizdacek['hakkimizda_baslik']; ?></div>
			</div>


			<div class="page-content">
				<p>
					<?php echo $hakkimizdacek['hakkimizda_icerik']; ?>
				</p>
				
			</div>
			
		</div>

		<?php include 'sidebar.php'; ?>
		<!-- sidebar buraya gelecek -->
	</div>
	<div class="spacer"></div>
</div>

<?php include 'footer.php'; ?>