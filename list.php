<?php include 'inc/top.php';$m=$_GET['m']??'berita';if(!in_array($m,['berita','wisata','umkm']))die;
$t=['berita'=>'Berita & Agenda','wisata'=>'Wisata Bahari','umkm'=>'Etalase UMKM'][$m];?>
<div class="container py-4"><h3 class="mb-3"><?=$t?></h3><div class="row g-3"><?php foreach(q("SELECT * FROM $m ORDER BY id DESC") as $r):?>
<div class="col-md-4"><div class="card h-100 shadow-sm"><?php if(!empty($r['gambar'])):?><img src="<?=e(imgSrc($r['gambar']))?>" referrerpolicy="no-referrer" class="card-img-top"><?php endif?><div class="card-body">
<h6><?=e($r['judul']??$r['nama'])?></h6><p class="small text-muted<?=in_array($m,['wisata','berita'])?' clamp3':''?>"><?=e($r['isi']??$r['deskripsi']??$r['nomor']??'')?></p>
<?php if($m=='wisata'):?><?=wisataBtn($r)?><?php endif?>
<?php if($m=='berita'):?><?=beritaBtn($r)?><?php endif?>
<?php if($m=='umkm'):?><b>Rp <?=number_format($r['harga'],0,',','.')?></b> <a class="btn btn-sm btn-success float-end" href="https://wa.me/<?=preg_replace('/^0/','62',preg_replace('/\D/','',$r['kontak']))?>">Hubungi</a><?php endif?>
</div></div></div><?php endforeach?></div></div>
<?php include 'inc/foot.php';?>
