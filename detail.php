<?php include 'inc/top.php';$m=($_GET['m']??'wisata')==='berita'?'berita':'wisata';$id=(int)($_GET['id']??0);$r=$id?q("SELECT * FROM $m WHERE id=?",[$id])->fetch():false;
if(!$r){http_response_code(404);echo '<div class="container py-5 text-center"><h4>Data tidak ditemukan</h4><a href="list.php?m='.$m.'" class="btn btn-warning btn-tix mt-2">Kembali</a></div>';include 'inc/foot.php';exit;}
$no=(int)q("SELECT COUNT(*) FROM $m WHERE id<=?",[$id])->fetchColumn();
if($m==='berita'){$judul=$r['judul'];$isi=$r['isi'];$poin=[];
 $bln=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];$t=strtotime($r['created_at']??'')?:null;
 $eyebrow=trim(sprintf('%02d',$no).' · Berita & Agenda'.($t?' · '.date('j',$t).' '.$bln[date('n',$t)-1].' '.date('Y',$t):''));}
else{$judul=$r['nama'];$isi=$r['deskripsi'];$eyebrow=trim(sprintf('%02d',$no).' · '.(trim((string)$r['jarak'])?:'Wisata Bahari'));
 $poin=array_values(array_filter(array_map('trim',preg_split('/\R/',(string)($r['aktivitas']??'')))));}?>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<div class="container py-4"><section class="dest"><div class="row g-4 g-lg-5 align-items-center">
<div class="col-lg-6"><div class="dest-img"><?php if($r['gambar']):?><img src="<?=e(imgSrc($r['gambar']))?>" referrerpolicy="no-referrer" alt="<?=e($judul)?>"><?php else:?><div class="dest-ph"><i class="bi bi-<?=$m==='berita'?'newspaper':'water'?>"></i></div><?php endif?></div></div>
<div class="col-lg-6"><div class="dest-eyebrow"><?=e($eyebrow)?></div><h2 class="dest-title"><?=e($judul)?></h2>
<p class="dest-desc"><?=e($isi)?></p>
<?php if($poin):?><ul class="dest-list"><?php foreach($poin as $p):?><li><span><i class="bi bi-check-lg"></i></span><?=e($p)?></li><?php endforeach?></ul><?php endif?>
<div class="d-flex flex-wrap gap-2 mt-4"><?php if($m==='wisata'):?><a class="btn btn-dest" href="<?=e(kunjungiUrl($r))?>" target="_blank" rel="noopener"><i class="bi bi-geo-alt-fill me-1"></i>Kunjungi Destinasi</a><?php endif?>
<a class="btn <?=$m==='wisata'?'btn-dest-out':'btn-dest'?>" href="list.php?m=<?=$m?>"><i class="bi bi-arrow-left"></i> Kembali</a></div></div>
</div></section></div>
<?php include 'inc/foot.php';?>
