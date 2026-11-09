<?php include 'inc/top.php';
$pts=q("SELECT * FROM peta")->fetchAll();?>
<?php $SL=array_map(fn($g)=>isUrlImg($g)?trim($g):$base.'/uploads/'.rawurlencode($g),q("SELECT gambar FROM hero_slide ORDER BY urut,id")->fetchAll(PDO::FETCH_COLUMN));
if(!$SL)$SL=['https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1600'];$n=count($SL);
?>
<section class="hero hs text-center">
<div class="hs-track"><?php foreach($SL as $i=>$u):?><div class="hs-slide<?=$i?'':' on'?>" style="background-image:url('<?=e($u)?>')"></div><?php endforeach?></div><div class="hs-shade"></div>
<?php if($n>1):?><button type="button" class="hs-nav hs-prev" aria-label="Foto sebelumnya"><i class="bi bi-chevron-left"></i></button><button type="button" class="hs-nav hs-next" aria-label="Foto berikutnya"><i class="bi bi-chevron-right"></i></button>
<div class="hs-dots"><?php for($i=0;$i<$n;$i++):?><button type="button" class="hs-dot<?=$i?'':' on'?>" aria-label="Foto <?=$i+1?>"></button><?php endfor?></div><?php endif?>
<div class="container hs-content"><span class="badge rounded-pill border border-warning text-warning mb-3 px-3 py-2"><?=e(S('wilayah'))?></span>
<h1 class="display-5 fw-bold"><?=e(S('hero_judul'))?></h1><p class="lead mx-auto" style="max-width:760px"><?=e(S('hero_sub'))?></p>
<a href="list.php?m=wisata" class="btn btn-warning btn-lg rounded-pill m-1"><i class="bi bi-water"></i> Eksplorasi Wisata</a>
<a href="list.php?m=berita" class="btn btn-outline-light btn-lg rounded-pill m-1">Baca Berita</a></div></section>
<div class="container qc"><div class="row g-3 text-center justify-content-center">
<?php /* icon & warna sama persis dengan menu/kartu di dashboard admin */
foreach([['list.php?m=berita','newspaper','#0d9488','Berita & Agenda'],['list.php?m=wisata','water','#0ea5e9','Wisata Bahari'],['#peta','geo-alt','#ef4444','Peta GIS'],['list.php?m=umkm','shop','#f59e0b','Etalase UMKM'],['struktur.php','diagram-3','#6366f1','Struktur Organisasi']] as $c):?>
<div class="col-6 col-md-4 col-lg-2"><a href="<?=$c[0]?>" class="text-decoration-none text-dark"><div class="card h-100 p-3" style="--c:<?=$c[2]?>"><div class="qc-ic mx-auto"><i class="bi bi-<?=$c[1]?>"></i></div><div class="fw-semibold mt-2"><?=$c[3]?></div></div></a></div><?php endforeach?></div></div>

<div class="container my-5"></div>
<div class="container"><h3 class="mb-3">Berita & Agenda</h3><div class="row g-3">
<?php foreach(q("SELECT * FROM berita ORDER BY id DESC LIMIT 3") as $b):?><div class="col-md-4"><div class="card h-100 shadow-sm"><?php if($b['gambar']):?><img src="<?=e(imgSrc($b['gambar']))?>" referrerpolicy="no-referrer" class="card-img-top"><?php endif?>
<div class="card-body"><h6><?=e($b['judul'])?></h6><p class="small text-muted clamp3"><?=e(mb_substr($b['isi'],0,120))?>...</p><?=beritaBtn($b)?></div></div></div><?php endforeach?></div>

<h3 class="mt-5 mb-3" id="peta">Peta GIS Bahari</h3><div id="legend" class="d-flex flex-wrap gap-2 mb-2"></div><div id="map"></div>

<h3 class="mt-5 mb-3">Wisata Bahari</h3><div class="row g-3">
<?php foreach(q("SELECT * FROM wisata ORDER BY id DESC LIMIT 3") as $w):?><div class="col-md-4"><div class="card h-100 shadow-sm"><?php if($w['gambar']):?><img src="<?=e(imgSrc($w['gambar']))?>" referrerpolicy="no-referrer" class="card-img-top"><?php endif?>
<div class="card-body"><h6><?=e($w['nama'])?></h6><p class="small text-muted clamp3"><?=e($w['deskripsi'])?></p><?=wisataBtn($w)?></div></div></div><?php endforeach?></div></div>
<?php $P=[];foreach($pts as $p){$k=$KAT[$p['kategori']]??$KAT['Lainnya'];$P[]=['n'=>$p['nama'],'k'=>$p['kategori'],'t'=>$p['keterangan'],'la'=>(float)$p['lat'],'ln'=>(float)$p['lng'],'i'=>$k[0],'c'=>$k[1]];}?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script><script>
const PTS=<?=json_encode($P,JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)?>,CEN={lat:-8.75,lng:115.9},mapEl=document.getElementById('map'),MK=[];
const esc=t=>String(t??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const info=p=>`<div style="min-width:180px"><b>${esc(p.n)}</b><br><span class="text-muted">${p.i} ${esc(p.k)}</span><br>${esc(p.t)}<div class="mt-2"><a href="https://www.google.com/maps/search/?api=1&query=${p.la},${p.ln}" target="_blank" rel="noopener">Buka di Google Maps</a> &middot; <a href="https://www.google.com/maps/dir/?api=1&destination=${p.la},${p.ln}" target="_blank" rel="noopener">Rute</a></div></div>`;
const pin=p=>{const d=document.createElement('div');d.className='pin';d.style.background=p.c;d.innerHTML='<span>'+p.i+'</span>';return d};
function legend(){const box=document.getElementById('legend'),cats=[...new Set(PTS.map(p=>p.k))];
 box.innerHTML='<button class="btn btn-sm btn-dark" data-k="">Semua</button>'+cats.map(k=>{const p=PTS.find(x=>x.k==k);return `<button class="btn btn-sm btn-outline-secondary" data-k="${esc(k)}">${p.i} ${esc(k)}</button>`}).join('');
 box.onclick=e=>{const b=e.target.closest('button');if(!b)return;box.querySelectorAll('button').forEach(x=>x.className='btn btn-sm '+(x==b?'btn-dark':'btn-outline-secondary'));
  MK.forEach(m=>m.show(!b.dataset.k||m.k==b.dataset.k))}}
function osm(){mapEl.innerHTML='';mapEl.className='';MK.length=0;const m=L.map('map').setView([CEN.lat,CEN.lng],11);
 L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap'}).addTo(m);
 PTS.forEach(p=>{const w=document.createElement('div');w.appendChild(pin(p));const mk=L.marker([p.la,p.ln],{icon:L.divIcon({html:w.innerHTML,className:'',iconSize:[38,38],iconAnchor:[19,46],popupAnchor:[0,-44]})}).bindPopup(info(p));mk.addTo(m);
  MK.push({k:p.k,show:v=>v?mk.addTo(m):m.removeLayer(mk)})});legend()}
<?php if(S('gmaps_key')):?>
window.gm_authFailure=()=>osm();
window.initMap=async()=>{const {AdvancedMarkerElement}=await google.maps.importLibrary('marker');
 const m=new google.maps.Map(mapEl,{center:CEN,zoom:11,mapId:'DEMO_MAP_ID',mapTypeControl:true,streetViewControl:true}),iw=new google.maps.InfoWindow();
 PTS.forEach(p=>{const w=document.createElement('div');w.style.transform='translateY(8px)';w.appendChild(pin(p));const mk=new AdvancedMarkerElement({map:m,position:{lat:p.la,lng:p.ln},content:w,title:p.n});
  mk.addListener('click',()=>{iw.setContent(info(p));iw.open({map:m,anchor:mk})});MK.push({k:p.k,show:v=>mk.map=v?m:null})});legend()};
</script><script async src="https://maps.googleapis.com/maps/api/js?key=<?=urlencode(S('gmaps_key'))?>&callback=initMap&loading=async&v=weekly"></script>
<?php else:?>
osm();</script>
<?php endif?>
<?php include 'inc/foot.php';?>
