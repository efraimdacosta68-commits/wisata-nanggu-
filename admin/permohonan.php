<?php require '_h.php';
$full=(isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'].$base.'/uploads/';
function pesan($r){global $full;$t="Halo {$r['nama']},\nPermohonan *{$r['jenis']}* (kode {$r['kode']}) status: *{$r['status']}*.";
 if($r['catatan'])$t.="\n{$r['catatan']}";if($r['hasil']&&!preg_match('/localhost|127\.0\.0\.1/',$full))$t.="\nUnduh surat: ".$full.$r['hasil'];return $t."\n- Pemerintah ".S('nama_desa');}
$id=(int)($_GET['id']??0);$info='';
if($id&&$_SERVER['REQUEST_METHOD']=='POST'){chk();q("UPDATE permohonan SET status=?,catatan=? WHERE id=?",[$_POST['status'],$_POST['catatan'],$id]);
 if($n=upload($_FILES['hasil']??[]))q("UPDATE permohonan SET hasil=? WHERE id=?",[$n,$id]);
 $r=q("SELECT * FROM permohonan WHERE id=?",[$id])->fetch();$info='Tersimpan.';
 if(!empty($_POST['kirim'])){$x=kirimWA($r['hp'],pesan($r),$r['hasil']?$full.$r['hasil']:null);$info=$x=='OK'?'Tersimpan & pesan terkirim ke WhatsApp pemohon.':"Tersimpan, tetapi WA gagal: $x";}}
if($id):$r=q("SELECT * FROM permohonan WHERE id=?",[$id])->fetch();if(!$r)die('Tidak ada');?>
<a href="permohonan.php">&larr; Kembali</a><h4 class="mt-2">Permohonan <?=e($r['kode'])?></h4>
<?php if($info):?><div class="alert alert-info"><?=e($info)?></div><?php endif?>
<?php if(preg_match('/localhost|127\.0\.0\.1/',$_SERVER['HTTP_HOST'])):?><div class="alert alert-warning small">Anda di localhost: pesan teks tetap terkirim, tetapi Fonnte tidak bisa mengambil file PDF dari komputer Anda. Lampiran surat baru terkirim setelah website online.</div><?php endif?>
<div class="row g-3"><div class="col-lg-5"><div class="card card-body shadow-sm"><b><?=e($r['nama'])?></b><span><?=e($r['jenis'])?></span><small>NIK: <?=e($r['nik'])?></small><small>HP/WA: <?=e($r['hp'])?></small><small class="text-muted"><?=e($r['keterangan'])?></small>
<?php if($r['lampiran']):?><a class="btn btn-sm btn-outline-secondary mt-2" target="_blank" href="../uploads/<?=e($r['lampiran'])?>">Lihat lampiran pemohon</a><?php endif?></div></div>
<div class="col-lg-7"><form method="post" enctype="multipart/form-data" class="card card-body shadow-sm"><input type="hidden" name="t" value="<?=csrf()?>">
<label class="small">Status</label><select name="status" class="form-select mb-2"><?php foreach(['Diproses','Siap Diambil','Selesai','Ditolak'] as $o):?><option <?=$r['status']==$o?'selected':''?>><?=$o?></option><?php endforeach?></select>
<label class="small">Catatan / pesan untuk pemohon</label><textarea name="catatan" class="form-control mb-2" rows="3"><?=e($r['catatan'])?></textarea>
<label class="small">Upload surat hasil (PDF/JPG, maks 5MB)</label><input type="file" name="hasil" class="form-control mb-2">
<?php if($r['hasil']):?><div class="small mb-2">File saat ini: <a target="_blank" href="../uploads/<?=e($r['hasil'])?>">buka surat</a></div><?php endif?>
<div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="kirim" value="1" id="kw" checked><label class="form-check-label small" for="kw">Kirim WhatsApp otomatis ke pemohon saat disimpan</label></div><div class="d-flex flex-wrap gap-2"><button class="btn btn-primary"><i class="bi bi-whatsapp"></i> Simpan &amp; Kirim WA</button>
<a class="btn btn-outline-success" target="_blank" href="https://wa.me/<?=wa62($r['hp'])?>?text=<?=rawurlencode(pesan($r))?>">Buka WhatsApp (manual)</a></div></form></div></div>
<?php else:?><h4>Permohonan Surat</h4><div class="table-responsive"><table class="table table-sm table-striped bg-white"><thead><tr><th>Kode</th><th>Nama</th><th>Jenis</th><th>HP</th><th>Status</th><th>Tanggal</th><th></th></tr></thead><tbody>
<?php foreach(q("SELECT * FROM permohonan ORDER BY id DESC LIMIT 300") as $r):?><tr><td><?=e($r['kode'])?></td><td><?=e($r['nama'])?></td><td><?=e($r['jenis'])?></td><td><?=e($r['hp'])?></td>
<td><span class="badge bg-<?=['Diproses'=>'warning','Selesai'=>'success','Ditolak'=>'danger'][$r['status']]??'info'?>"><?=e($r['status'])?></span></td><td><?=e(substr($r['created_at'],0,10))?></td><td><a class="btn btn-sm btn-outline-primary" href="permohonan.php?id=<?=$r['id']?>">Kelola</a></td></tr><?php endforeach?></tbody></table></div><?php endif?>
</div></div></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>
