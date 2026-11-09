<?php
session_start();
$db=new PDO('mysql:host=localhost;dbname=desa_tawun;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$base=rtrim(str_replace('/admin','',dirname($_SERVER['SCRIPT_NAME'])),'/');
try{if(!$db->query("SHOW COLUMNS FROM wisata LIKE 'link_tiket'")->fetch())$db->exec("ALTER TABLE wisata ADD link_tiket VARCHAR(500) NULL AFTER gambar");}catch(Exception $x){}
foreach(['jarak'=>'VARCHAR(120) NULL','aktivitas'=>'TEXT NULL'] as $c=>$def){try{if(!$db->query("SHOW COLUMNS FROM wisata LIKE '$c'")->fetch())$db->exec("ALTER TABLE wisata ADD $c $def");}catch(Exception $x){}}
foreach(['berita','wisata','umkm'] as $t){try{$c=$db->query("SELECT CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='$t' AND COLUMN_NAME='gambar'")->fetchColumn();if($c&&$c<1000)$db->exec("ALTER TABLE $t MODIFY gambar VARCHAR(1000) NULL");}catch(Exception $x){}}
try{if(!$db->query("SHOW TABLES LIKE 'hero_slide'")->fetch()){$db->exec("CREATE TABLE hero_slide(id INT AUTO_INCREMENT PRIMARY KEY,gambar VARCHAR(100),urut INT DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");$o=$db->query("SELECT v FROM settings WHERE k='hero_bg'")->fetchColumn();if($o)$db->prepare("INSERT INTO hero_slide(gambar,urut) VALUES(?,1)")->execute([$o]);}}catch(Exception $x){}
try{if(!$db->query("SHOW TABLES LIKE 'logo_footer'")->fetch())$db->exec("CREATE TABLE logo_footer(id INT AUTO_INCREMENT PRIMARY KEY,gambar VARCHAR(100),urut INT DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");}catch(Exception $x){}
try{$c=$db->query("SELECT CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='hero_slide' AND COLUMN_NAME='gambar'")->fetchColumn();if($c&&$c<1000)$db->exec("ALTER TABLE hero_slide MODIFY gambar VARCHAR(1000) NULL");}catch(Exception $x){}
try{if(!$db->query("SHOW TABLES LIKE 'struktur'")->fetch())$db->exec("CREATE TABLE struktur(id INT AUTO_INCREMENT PRIMARY KEY,nama VARCHAR(150),jabatan VARCHAR(150),tingkat VARCHAR(50) DEFAULT 'Anggota',gambar VARCHAR(1000) NULL,urut INT DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");}catch(Exception $x){}
$KAT=['Kantor'=>['🏛️','#0b4f4a'],'Wisata'=>['🏝️','#0ea5e9'],'Warung Makan'=>['🍽️','#f59e0b'],'Penginapan'=>['🛏️','#8b5cf6'],'Masjid / Ibadah'=>['🕌','#16a34a'],'Dermaga'=>['⚓','#0369a1'],'Toko / Oleh-oleh'=>['🛍️','#ec4899'],'SPBU / Bengkel'=>['⛽','#ef4444'],'Toilet / Fasilitas'=>['🚻','#64748b'],'Kesehatan'=>['🏥','#dc2626'],'Lainnya'=>['📍','#475569']];
function tiket($u){$u=trim((string)$u);if($u==='')return '';if(!preg_match('~^https?://~i',$u))$u='https://'.$u;return filter_var($u,FILTER_VALIDATE_URL)?$u:'';}
function q($s,$p=[]){global $db;$st=$db->prepare($s);$st->execute($p);return $st;}
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES);}
function S($k){static $c;if($c===null)$c=q("SELECT k,v FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);return $c[$k]??'';}
function upload($f,$max=5e6){if(empty($f['name'])||$f['error'])return null;$x=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION));
 if(!in_array($x,['jpg','jpeg','png','webp','pdf'])||$f['size']>$max)return null;$n=bin2hex(random_bytes(10)).".$x";move_uploaded_file($f['tmp_name'],__DIR__."/uploads/$n");return $n;}
function isUrlImg($g){return (bool)preg_match('~^https?://~i',trim((string)$g));}
function imgSrc($g){$g=trim((string)$g);return isUrlImg($g)?$g:'uploads/'.$g;} // foto: link luar ATAU file hasil upload
function urlFoto($u){$u=trim((string)$u);if(!preg_match('~^https?://~i',$u)||!filter_var($u,FILTER_VALIDATE_URL)||strlen($u)>1000)return '';
 if(preg_match('~drive\.google\.com/(?:file/d/|open\?id=|uc\?(?:[^#]*&)?id=)([\w-]{10,})~i',$u,$m))return 'https://drive.google.com/thumbnail?id='.$m[1].'&sz=w2000'; // link share Google Drive -> gambar langsung
 return $u;}
function csrf(){return $_SESSION['t']??=bin2hex(random_bytes(16));}
function chk(){if(!hash_equals(csrf(),$_POST['t']??''))die('CSRF');}

function wa62($h){$h=preg_replace('/\D/','',(string)$h);return $h&&$h[0]=='0'?'62'.substr($h,1):$h;}
function kirimWA($hp,$msg,$url=null){$tok=trim(S('fonnte_token'));if(!$tok)return 'Token Fonnte belum diisi di Pengaturan Situs.';if(!function_exists('curl_init'))return 'Ekstensi cURL PHP belum aktif.';
 $d=['target'=>wa62($hp),'message'=>$msg,'countryCode'=>'62'];if($url&&!preg_match('~//(localhost|127\.|192\.168\.|10\.|[^/]*\.local)~i',$url)){$d['url']=$url;$d['filename']=basename($url);}
 $ch=curl_init('https://api.fonnte.com/send');curl_setopt_array($ch,[CURLOPT_POST=>1,CURLOPT_POSTFIELDS=>$d,CURLOPT_HTTPHEADER=>["Authorization: $tok"],CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>15]);
 $r=json_decode(curl_exec($ch),true);return !empty($r['status'])?'OK':($r['reason']??'Gagal terhubung ke Fonnte');}
function fetchFoto($u,$max=10e6){ // unduh gambar dari link lalu simpan di uploads (anti diblokir/hilang). Null = link bukan gambar langsung / tidak bisa diakses
 if(!function_exists('curl_init'))return $u;
 $ch=curl_init($u);curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_FOLLOWLOCATION=>1,CURLOPT_MAXREDIRS=>5,CURLOPT_TIMEOUT=>25,CURLOPT_CONNECTTIMEOUT=>10,CURLOPT_SSL_VERIFYPEER=>0,CURLOPT_USERAGENT=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120 Safari/537.36',CURLOPT_HTTPHEADER=>['Accept: image/avif,image/webp,image/*,*/*;q=0.8']]);
 $d=curl_exec($ch);$code=curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
 if($d===false||$code>=400||strlen($d)>$max)return null;
 $i=@getimagesizefromstring($d);$ext=[IMAGETYPE_JPEG=>'jpg',IMAGETYPE_PNG=>'png',IMAGETYPE_WEBP=>'webp'][$i[2]??0]??null;if(!$ext)return null;
 $n=bin2hex(random_bytes(10)).".$ext";return file_put_contents(__DIR__."/uploads/$n",$d)?$n:null;}
function uploadMany($key,$max=10e6){$o=['ok'=>[],'gagal'=>0];$f=$_FILES[$key]??null;if(!$f||!is_array($f['name']))return $o;
 foreach($f['name'] as $i=>$nm){if($nm==='')continue;$x=strtolower(pathinfo($nm,PATHINFO_EXTENSION));
  $n=in_array($x,['jpg','jpeg','png','webp'])?upload(['name'=>$nm,'tmp_name'=>$f['tmp_name'][$i],'error'=>$f['error'][$i],'size'=>$f['size'][$i]],$max):null;
  if($n)$o['ok'][]=$n;else $o['gagal']++;}return $o;}
function wisataBtn($r){ // "Lihat Selengkapnya" -> halaman detail (deskripsi + tombol Kunjungi Destinasi)
 return '<div class="d-flex flex-wrap gap-2 mt-2"><a class="btn btn-sm btn-warning btn-tix" href="detail.php?m=wisata&id='.(int)$r['id'].'"><i class="bi bi-arrow-right-circle"></i> Lihat Selengkapnya</a></div>';}
function beritaBtn($r){return '<div class="d-flex flex-wrap gap-2 mt-2"><a class="btn btn-sm btn-warning btn-tix" href="detail.php?m=berita&id='.(int)$r['id'].'"><i class="bi bi-arrow-right-circle"></i> Baca Selengkapnya</a></div>';}
function kunjungiUrl($r){ // link tombol "Kunjungi Destinasi": link dari admin; jika kosong -> Google Maps (koordinat peta / pencarian nama)
 $tk=tiket($r['link_tiket']??'');if($tk)return $tk;
 $p=q("SELECT lat,lng FROM peta WHERE nama=? LIMIT 1",[$r['nama']])->fetch();
 if($p&&$p['lat']!==null&&$p['lng']!==null)return 'https://www.google.com/maps/search/?api=1&query='.$p['lat'].','.$p['lng'];
 return 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($r['nama'].' '.S('wilayah'));}
