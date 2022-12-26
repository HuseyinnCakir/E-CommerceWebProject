<div class="col-md-3"><!--sidebar-->
	<div class="title-bg">
		<div class="title">Kategoriler</div>
	</div>
	
	<div class="categorybox">
		
		<ul>
			
					<?php 

//bütün kayıtları bir kereye mahsus olmak üzere listeliyoruz; daha doğrusu, bir diziye aktarmak için verileri çekiyoruz

			$query = "SELECT * FROM kategori order by kategori_id";
			$goster = $db->prepare($query);
$goster->execute(); //queriyi tetikliyor

 $toplamSatirSayisi = $goster->rowCount(); //malumunuz üzere sorgudan dönen satır sayısını öğreniyoruz
 
$tumSonuclar = $goster->fetchAll(); //DB'deki bütün satırları ve sutunları $tumSonuclar değişkenine dizi şeklinde aktarıyoruz


//alt kategorisi olmayan kategorileri sayısını öğreniyoruz:
$altKategoriSayisi = 0;
for ($i = 0; $i < $toplamSatirSayisi; $i++) {
	if ($tumSonuclar[$i]['kategori_ust'] == "0") {
		$altKategoriSayisi++;
	}
}

for ($i = 0; $i < $toplamSatirSayisi; $i++) {
	if ($tumSonuclar[$i]['kategori_ust'] == "0") {
		kategori($tumSonuclar[$i]['kategori_id'], $tumSonuclar[$i]['kategori_ad'], $tumSonuclar[$i]['kategori_ust']);
	}
}

function kategori($kategori_id, $kategori_ad, $kategori_ust) {

	global $tumSonuclar;
	global $toplamSatirSayisi;

    //kategorinin, alt kategori sayısını öğreniyoruz:
	$altKategoriSayisi = 0;
	for ($i = 0; $i < $toplamSatirSayisi; $i++) {
		if ($tumSonuclar[$i]['kategori_ust'] == $kategori_id) {
			$altKategoriSayisi++;
		}
	}
    ///////////////////////////////////////////

	?>

	<!-- Burda Başlıyoruz ana gövde -->

	<li>
		<?php if($kategori_ust==0){ ?>
		<a href="kategori-<?=seo($kategori_ad) ?>"><?php echo $kategori_ad ?></a>
		<?php }
		else{?>
				<a href="altkategori-<?=seo($kategori_ad) ?>"><?php echo $kategori_ad ?></a>
		<?php } ?> 
	</a>

	<?php

    if ($altKategoriSayisi > 0) { //alt kategorisi varsa onları da listele
    	echo "<ul>";

    	for ($i = 0; $i < $toplamSatirSayisi; $i++) {

    		if ($tumSonuclar[$i]['kategori_ust'] == $kategori_id) {
    			
    			kategori($tumSonuclar[$i]['kategori_id'], $tumSonuclar[$i]['kategori_ad'], $tumSonuclar[$i]['kategori_ust']);
    		}
    	}

    	echo "</ul>";
    }
    ?>
</li> 
<!-- Burda Başlıyoruz ana gövde -->

<?php 
}
?>
		</ul>
	</div>

	<!-- Kategoriler yukarıda -->


<?php 
$urunsor=$db->prepare("SELECT * FROM urun  order by urun_id DESC limit 2,3");
                     	$urunsor->execute();
                     	
 ?>
	
	<div class="title-bg">
		<div class="title">En Çok Satanlar</div>
	</div>
	<div class="best-seller">

		<ul>
			<?php while($uruncek=$urunsor->fetch(PDO::FETCH_ASSOC)){ 
				$urun_id=$uruncek['urun_id'];
						$urunfotosor=$db->prepare("SELECT urunfoto_resimyol FROM urunfoto WHERE urun_id=:urun_id ORDER BY urunfoto_sira ASC LIMIT 1");
						$urunfotosor->execute(['urun_id'=> $urun_id]);
						$urunfotocek=$urunfotosor->fetch(PDO::FETCH_ASSOC);
				?>
			<li class="clearfix">
				<a href="urun-<?=seo($uruncek["urun_ad"]).'-'.$uruncek["urun_id"]?>"><img src="<?php if(isset($urunfotocek["urunfoto_resimyol"])) echo $urunfotocek["urunfoto_resimyol"]; else{ echo "dimg/logo-yok.png"; } ?>" alt=""  class="img-responsive mini"></a>
				<div class="mini-meta">
					<a href="urun-<?=seo($uruncek["urun_ad"]).'-'.$uruncek["urun_id"]?>" class="smalltitle2"><?php echo $uruncek['urun_ad']; ?></a>
					<p class="smallprice2">Fiyat : <?php echo $uruncek['urun_fiyat']; ?>TL</p>
				</div>
			</li>
			<?php } ?>
		</ul>
	
	</div>

			</div><!--sidebar-->