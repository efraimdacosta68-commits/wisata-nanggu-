<?php require_once 'inc/surat.php';ajaxSurat();$noModal=true;include 'inc/top.php';$msg='';
if($_SERVER['REQUEST_METHOD']=='POST'){[$k,$err]=simpanPermohonan($_POST,$_FILES);
 $msg=$err?"<div class='alert alert-danger'>".e($err)."</div>":"<div class='alert alert-success'>Permohonan terkirim. Kode pelacakan Anda: <b>$k</b></div>";}
$cek=isset($_GET['kode'])?q("SELECT * FROM permohonan WHERE kode=?",[$_GET['kode']])->fetch():null;?>
<div class="container py-4"><h3>Layanan E-Surat</h3><?=$msg?><div class="row g-4"><div class="col-lg-7"><form method="post" enctype="multipart/form-data" class="card card-body shadow-sm">
<label class="form-label">Jenis Layanan</label><select name="jenis" class="form-select mb-2" required><?php foreach(q("SELECT * FROM jenis_layanan") as $j):?><option><?=e($j['nama'])?></option><?php endforeach?></select>
<input name="nik" class="form-control mb-2" placeholder="NIK (16 digit)" maxlength="16" pattern="\d{16}" required><input name="nama" class="form-control mb-2" placeholder="Nama lengkap" required>
<input name="hp" class="form-control mb-2" placeholder="No. HP / WhatsApp" required><textarea name="ket" class="form-control mb-2" placeholder="Keterangan"></textarea>
<label class="form-label small">Lampiran persyaratan (JPG/PDF, maks 5MB)</label><input type="file" name="lamp" class="form-control mb-3"><button class="btn btn-success">Kirim Permohonan</button></form></div>
<div class="col-lg-5"><div class="card card-body shadow-sm mb-3"><h6>Cek Status</h6><form class="input-group"><input name="kode" class="form-control" placeholder="Kode permohonan"><button class="btn btn-outline-success">Cek</button></form>
<?php if($cek):?><div class="mt-2">Status: <b><?=e($cek['status'])?></b><br><small><?=e($cek['catatan'])?></small><?php if($cek['hasil']):?><br><a class="btn btn-sm btn-success mt-2" href="uploads/<?=e($cek['hasil'])?>"><i class="bi bi-download"></i> Unduh Surat</a><?php endif?></div><?php elseif(isset($_GET['kode'])):?><div class="mt-2 text-danger">Kode tidak ditemukan.</div><?php endif?></div>
<div class="card card-body shadow-sm"><h6>Persyaratan</h6><?php foreach(q("SELECT * FROM jenis_layanan") as $j):?><div class="small mb-1"><b><?=e($j['nama'])?></b>: <?=e($j['syarat'])?></div><?php endforeach?></div></div></div></div>
<?php include 'inc/foot.php';?>
