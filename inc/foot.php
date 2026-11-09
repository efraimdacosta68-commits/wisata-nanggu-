<?php $LG=q("SELECT gambar FROM logo_footer ORDER BY urut,id")->fetchAll(PDO::FETCH_COLUMN);?>
<footer class="pt-5 pb-3 mt-5"><div class="container"><div class="row g-4">
<div class="col-md-7"><h5 class="text-white d-flex align-items-center"><?php if(S('logo')):?><img src="<?=$base?>/uploads/<?=e(S('logo'))?>" alt="Logo" height="44" class="me-2 rounded bg-white p-1"><?php endif?>Pariwisata <?=e(S('nama_desa'))?></h5><small><?=e(S('wilayah'))?></small>
<p class="mt-2 small"><i class="bi bi-geo-alt"></i> <?=e(S('alamat'))?><br><i class="bi bi-whatsapp"></i> <?=e(S('wa'))?> | <i class="bi bi-envelope"></i> <?=e(S('email'))?></p></div>
<div class="col-md-5"><h6 class="text-white">Media</h6><div class="small"><i class="bi bi-instagram"></i> <?=e(S('instagram'))?></div><div class="small mt-2"><i class="bi bi-clock"></i> <?=e(S('jam'))?></div></div></div>
<hr><div class="d-flex flex-wrap align-items-end justify-content-between gap-3"><small>Hak Cipta © <?=date('Y')?> Pariwisata <?=e(S('nama_desa'))?>. Seluruh Hak Cipta Dilindungi.</small>
<?php if($LG):?><div class="foot-logos d-flex flex-wrap align-items-center justify-content-end gap-2 ms-auto"><?php foreach($LG as $g):?><img src="<?=$base?>/uploads/<?=e($g)?>" alt="Logo mitra" height="48" class="rounded bg-white p-1"><?php endforeach?></div><?php endif?></div></div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="<?=$base?>/assets/ui.js?v=<?=filemtime(__DIR__."/../assets/ui.js")?>"></script></body></html>
