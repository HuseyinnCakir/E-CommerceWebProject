<?php 

include 'header.php'; 
     $sayfada = 9; // sayfada gösterilecek içerik miktarını belirtiyoruz.
                     $sorgu=$db->prepare("select * from urun");
                     $sorgu->execute();
                     $toplam_icerik=$sorgu->rowCount();
                     $toplam_sayfa = ceil($toplam_icerik / $sayfada);
                  	// eğer sayfa girilmemişse 1 varsayalım.
                     $sayfa = isset($_GET['sayfa']) ? (int) $_GET['sayfa'] : 1;
          			// eğer 1'den küçük bir sayfa sayısı girildiyse 1 yapalım.
                     if($sayfa < 1) $sayfa = 1; 
        				// toplam sayfa sayımızdan fazla yazılırsa en son sayfayı varsayalım.
                     if($sayfa > $toplam_sayfa) $sayfa = $toplam_sayfa; 
                     $limit = ($sayfa - 1) * $sayfada;



                     //aşağısı bir önceki default kodumuz...
                     if (isset($_GET['sef'])) {


                     	$kategorisor=$db->prepare("SELECT * FROM kategori where kategori_seourl=:seourl");
                     	$kategorisor->execute(array(
                     		'seourl' => $_GET['sef']
                     		));

                     	$kategoricek=$kategorisor->fetch(PDO::FETCH_ASSOC);

                     	$kategori_id=$kategoricek['kategori_id'];


                     	$urunsor=$db->prepare("SELECT * FROM urun where kategori_id=:kategori_id order by urun_id DESC limit $limit,$sayfada");
                     	$urunsor->execute(array(
                     		'kategori_id' => $kategori_id
                     		));

                     	$say=$urunsor->rowCount();
                     	$toplam_sayfa = ceil($say / $sayfada);

                     }

                     	else if (isset($_GET['sef2']) ) {
                     	
                     	
                     	$kategorisor=$db->prepare("SELECT * FROM kategori where kategori_seourl=:seourl");
                     	$kategorisor->execute(array(
                     		'seourl' => $_GET['sef2']
                     		));

                     	$kategoricek=$kategorisor->fetch(PDO::FETCH_ASSOC);

                     	
                         $urun_seourl=$kategoricek['kategori_seourl'];

                     	$urunsor=$db->prepare("SELECT * FROM urun where urun_seourl=:urun_seourl order by urun_id DESC LIMIT $limit,$sayfada");
                     	$urunsor->execute(array(
                     		'urun_seourl' => $urun_seourl
                     		));

                     	$say=$urunsor->rowCount();
                     	
                     	
                     	$toplam_sayfa = ceil($say / $sayfada);

                     } 



                      else {

                     	$urunsor=$db->prepare("SELECT * FROM urun order by urun_id DESC limit $limit,$sayfada");
                     	$urunsor->execute();
                     	$say=$urunsor->rowCount();
                     }


                     if ($say==0) {
                     	echo "Bu kategoride ürün bulunamadı";
                     }

?>
<head>	
<title><?php if(isset($_GET['sef2']) || isset($_GET['sef'])) echo $kategoricek['kategori_ad']; else { echo "Kategoriler"; }?></title></head>
<div class="container">

	<div class="clearfix"></div>
	<div class="lines"></div>
</div>

<div class="container">

	<div class="row">
		<div class="col-md-9"><!--Main content-->
			<div class="title-bg">
				<div class="title">Ürünler</div>
				
			</div>
			<div class="row prdct"><!--Products-->


				<?php

                     while($uruncek=$urunsor->fetch(PDO::FETCH_ASSOC)) {
				$urun_id=$uruncek['urun_id'];
				$urunfotosor=$db->prepare("SELECT urunfoto_resimyol FROM urunfoto WHERE urun_id=:urun_id ORDER BY urunfoto_sira ASC LIMIT 1");
				$urunfotosor->execute(['urun_id'=> $urun_id]);
				$urunfotocek=$urunfotosor->fetch(PDO::FETCH_ASSOC);
                     	?>

                     	<div class="col-md-4">
                     		<div class="productwrap">
                     			<div class="pr-img">
                     				<div class="hot"></div>
                     				<a href="urun-<?=seo($uruncek["urun_ad"]).'-'.$uruncek["urun_id"]?>"><img src="<?php if(isset($urunfotocek["urunfoto_resimyol"])) echo $urunfotocek["urunfoto_resimyol"]; else{ echo "dimg/logo-yok.png"; } ?>" alt="" style="width:200px;height: 200px" class="img-responsive"></a>
                     				<div class="pricetag on-sale"><div class="inner on-sale"><span class="onsale"><span class="oldprice"><?php echo $uruncek['urun_fiyat']*1.50 ?>TL</span><?php echo $uruncek['urun_fiyat'] ?>TL</span></div></div>
                     			</div>
                     			<span class="smalltitle"><a href="product.htm"><?php echo $uruncek['urun_ad'] ?></a></span>
                     			<span class="smalldesc">Ürün Kodu.: <?php echo $uruncek['urun_id'] ?></span>
                     		</div>
                     	</div>


                     	<?php } ?>

                     	<div align="right" class="col-md-12">
                     		<ul class="pagination">

                     			<?php

                     			$s=0;

                     			while ($s < $toplam_sayfa) {

                     				$s++; ?>

                     				<?php 

                     				if ($s==$sayfa) {?>

                     				<li class="active">

                     					<a href="kategoriler?sayfa=<?php echo $s; ?>"><?php echo $s; ?></a>
                     				</li>
                     				<?php } else {?>
                     				<li>

                     					<a href="kategoriler?sayfa=<?php echo $s; ?>"><?php echo $s; ?></a>

                     				</li>

                     				<?php   }
                     			}

                     			?>

                     		</ul>
                     	</div>


                     </div><!--Products-->

<!-- 
				<ul class="pagination shop-pag">
					<li><a href="#"><i class="fa fa-caret-left"></i></a></li>
					<li><a href="#">1</a></li>
					<li><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li><a href="#">4</a></li>
					<li><a href="#">5</a></li>
					<li><a href="#"><i class="fa fa-caret-right"></i></a></li>
				</ul> -->

			</div>

			<?php include 'sidebar.php' ?>
		</div>
		<div class="spacer"></div>
	</div>
	
	<?php include 'footer.php'; ?>