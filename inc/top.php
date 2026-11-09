<?php require_once __DIR__.'/../config.php';?><!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e(S('nama_desa'))?> - Pariwisata &amp; Berita Desa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
<style>.hero{color:#fff}
.qc{margin-top:-80px}.qc .card{border:0;border-radius:16px;box-shadow:0 8px 24px #0002;transition:.2s}.qc .card:hover{transform:translateY(-4px)}
.stat{background:linear-gradient(135deg,#0b4f4a,#0d9488);color:#fff;border-radius:24px}.stat .b{background:#ffffff1a;border-radius:14px;padding:18px}
footer{background:linear-gradient(#0b4f4a,#06302d);color:#d5ece9}footer a{color:#d5ece9;text-decoration:none}#map{height:420px;border-radius:16px}.card img{height:180px;object-fit:cover}</style><link href="<?=$base?>/assets/ui.css?v=<?=filemtime(__DIR__."/../assets/ui.css")?>" rel="stylesheet">
</head><body>
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top"><div class="container"><a class="navbar-brand fw-bold text-success d-flex align-items-center" href="<?=$base?>/"><?php if(S('logo')):?><img src="<?=$base?>/uploads/<?=e(S('logo'))?>" alt="Logo" height="40" class="me-2 rounded"><?php else:?><i class="bi bi-water me-2"></i><?php endif?><?=e(S('nama_desa'))?></a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#n"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="n"><ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link" href="<?=$base?>/">Beranda</a></li>
<li class="nav-item"><a class="nav-link" href="<?=$base?>/list.php?m=berita">Berita</a></li><li class="nav-item"><a class="nav-link" href="<?=$base?>/list.php?m=wisata">Wisata</a></li><li class="nav-item"><a class="nav-link" href="<?=$base?>/struktur.php">Struktur</a></li>
<li class="nav-item"><a class="nav-link" href="<?=$base?>/#peta">Peta GIS</a></li><li class="nav-item"><a class="nav-link" href="<?=$base?>/list.php?m=umkm">UMKM</a></li>
<li class="nav-item"><a class="btn btn-sm btn-outline-success ms-lg-2" href="<?=$base?>/admin/">Admin</a></li></ul></div></div></nav>
