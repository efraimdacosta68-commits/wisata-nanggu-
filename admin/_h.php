<?php require_once '../config.php';if(empty($_SESSION['adm'])){header('Location: login.php');exit;}
$M=['berita'=>['Berita & Agenda',['judul'=>'text','isi'=>'textarea','gambar'=>'file']],
'wisata'=>['Wisata Bahari',['nama'=>'text','deskripsi'=>'textarea','gambar'=>'file','link_tiket'=>'text','jarak'=>'text','aktivitas'=>'textarea']],
'umkm'=>['Etalase UMKM',['nama'=>'text','pemilik'=>'text','harga'=>'number','kontak'=>'text','deskripsi'=>'textarea','gambar'=>'file']],
'struktur'=>['Struktur Organisasi',['nama'=>'text','jabatan'=>'text','tingkat'=>'Pembina,Ketua,Wakil Ketua,Sekretaris,Bendahara,Koordinator Bidang,Anggota','urut'=>'number','gambar'=>'file']],
'peta'=>['Peta GIS',['nama'=>'text','kategori'=>implode(',',array_keys($KAT)),'lat'=>'text','lng'=>'text','keterangan'=>'textarea']]];?>

<?php $cur=basename($_SERVER['SCRIPT_NAME'],'.php');$cm=$_GET['m']??'';$IC=['berita'=>'bi-newspaper','wisata'=>'bi-water','umkm'=>'bi-shop','peta'=>'bi-geo-alt','struktur'=>'bi-diagram-3'];
$act=fn($c)=>$c?' active':'';?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin - <?=e(S('nama_desa'))?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="<?=$base?>/assets/ui.css" rel="stylesheet"><link href="<?=$base?>/assets/admin.css" rel="stylesheet"></head><body class="adm">
<nav class="navbar navbar-dark adm-nav py-2"><div class="container-fluid"><button class="navbar-toggler d-lg-none me-2" data-bs-toggle="offcanvas" data-bs-target="#sb"><span class="navbar-toggler-icon"></span></button>
<a class="navbar-brand d-flex align-items-center me-auto" href="index.php"><?php if(S('logo')):?><img src="<?=$base?>/uploads/<?=e(S('logo'))?>" alt="Logo" height="38" class="me-2 rounded bg-white p-1"><?php else:?><i class="bi bi-speedometer2 me-2"></i><?php endif?>Dashboard Admin</a><a class="btn btn-sm btn-web" href="../" target="_blank"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat Web</a></div></nav>
<div class="container-fluid"><div class="row"><div class="col-lg-2 p-0 adm-side"><div class="offcanvas-lg offcanvas-start" id="sb"><div class="list-group list-group-flush w-100">
<div class="side-title">Menu</div>
<a class="list-group-item<?=$act($cur=='index')?>" href="index.php"><i class="bi bi-grid-1x2"></i>Ringkasan</a><a class="list-group-item<?=$act($cur=='settings')?>" href="settings.php"><i class="bi bi-gear"></i>Pengaturan Situs</a>
<div class="side-title">Kelola Data</div>
<?php foreach($M as $k=>$v):?><a class="list-group-item<?=$act($cm==$k)?>" href="crud.php?m=<?=$k?>"><i class="bi <?=$IC[$k]??'bi-folder'?>"></i><?=$v[0]?></a><?php endforeach?>
<div class="side-title">Akun</div>
<a class="list-group-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i>Keluar</a></div></div></div>
<div class="col-lg-10 py-4 px-3 px-lg-4 adm-main">
