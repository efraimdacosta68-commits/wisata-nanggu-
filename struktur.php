<?php include 'inc/top.php';
$RW=[['Pembina'],['Ketua'],['Wakil Ketua'],['Sekretaris','Bendahara'],['Koordinator Bidang'],['Anggota']];
$all=q("SELECT * FROM struktur ORDER BY urut,id")->fetchAll();$by=[];$known=array_merge(...$RW);
foreach($all as $r){$k=in_array($r['tingkat'],$known)?$r['tingkat']:'Anggota';$by[$k][]=$r;}
$judul=S('struktur_judul')?:'Struktur Organisasi Karang Taruna';$per=S('struktur_periode');
function ini($n){$p=preg_split('/\s+/',trim($n));return mb_strtoupper(mb_substr($p[0]??'',0,1).(count($p)>1?mb_substr(end($p),0,1):''));}?>
<div class="container py-4"><div class="text-center mb-4"><span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 mb-2"><i class="bi bi-diagram-3"></i> Struktur Organisasi</span>
<h2 class="fw-bold"><?=e($judul)?></h2><?php if($per):?><div class="text-muted"><?=e($per)?></div><?php endif?><div class="text-muted small"><?=e(S('nama_desa'))?> · <?=e(S('wilayah'))?></div></div>
<?php if(!$all):?><div class="text-center text-muted py-5">Struktur organisasi belum diisi.</div><?php endif?>
<div class="org"><?php foreach($RW as $row):$cards=[];foreach($row as $k)foreach($by[$k]??[] as $r)$cards[]=$r;if(!$cards)continue;?>
<div class="org-row"><?php foreach($cards as $r):?><div class="org-card"><div class="org-ph"><?php if(!empty($r['gambar'])):?><img src="<?=e(imgSrc($r['gambar']))?>" referrerpolicy="no-referrer" alt="<?=e($r['nama'])?>"><?php else:?><span><?=e(ini($r['nama']))?></span><?php endif?></div>
<div class="org-nm"><?=e($r['nama'])?></div><div class="org-jb"><?=e($r['jabatan'])?></div></div><?php endforeach?></div><?php endforeach?></div></div>
<?php include 'inc/foot.php';?>
