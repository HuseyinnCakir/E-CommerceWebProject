<?php include 'header.php';
//BELİRLİ VERİYİ SEÇME İŞLEMİ

$menusor=$db->prepare("SELECT menu_id,menu_ad,menu_detay FROM menu where menu_seourl=:sef");
$menusor->execute(['sef' => $_GET['sef']]);
$menucek=$menusor->fetch(PDO::FETCH_ASSOC);

?>
<head>	
<title><?php echo $menucek['menu_ad'] ?></title></head>
<div class="container">
	
	<div class="row">
		<div class="col-md-9"><!--Main content-->
			<div class="title-bg">
				<div class="title"><?php echo $menucek['menu_ad']; ?></div>
			</div>
			



			<div class="page-content">
				<p>
					<?php echo $menucek['menu_detay']; ?>
				</p>
				
			</div>
			
		</div>

		<?php include 'sidebar.php'; ?>
		<!-- sidebar buraya gelecek -->
	</div>
	<div class="spacer"></div>
</div>

<?php include 'footer.php'; ?>