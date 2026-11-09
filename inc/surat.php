<?php
// Logika bersama Layanan E-Surat (dipakai layanan.php dan popup di beranda)
require_once __DIR__.'/../config.php';

// Simpan permohonan baru. Mengembalikan [kode, pesanError].
function simpanPermohonan($P,$F){
 global $base;
 $nik=preg_replace('/\D/','',$P['nik']??'');$nama=trim($P['nama']??'');$hp=trim($P['hp']??'');$jenis=trim($P['jenis']??'');
 if(strlen($nik)!=16)return [null,'NIK harus 16 digit angka.'];
 if($nama===''||$hp===''||$jenis==='')return [null,'Jenis layanan, nama, dan nomor HP wajib diisi.'];
 $k='TWN'.date('ymd').rand(100,999);
 q("INSERT INTO permohonan(kode,jenis,nik,nama,hp,keterangan,lampiran) VALUES(?,?,?,?,?,?,?)",[$k,$jenis,$nik,$nama,$hp,$P['ket']??'',upload($F['lamp']??[])]);
 $ba=(isset($_SERVER['HTTPS'])?'https':'http').'://'.$_SERVER['HTTP_HOST'].$base;
 kirimWA($hp,"Halo $nama,\nPermohonan *$jenis* Anda sudah kami terima.\nKode pelacakan: *$k*\nCek status: $ba/layanan.php?kode=$k\n- Pemerintah ".S('nama_desa'));
 if(S('wa'))kirimWA(S('wa'),"[Permohonan Baru] $k\n$jenis - $nama ($hp)\nKelola: $ba/admin/permohonan.php");
 return [$k,null];
}

// Permintaan AJAX dari popup beranda -> balas JSON lalu berhenti
function ajaxSurat(){
 if(empty($_SERVER['HTTP_X_REQUESTED_WITH']))return;
 header('Content-Type: application/json; charset=utf-8');
 if($_SERVER['REQUEST_METHOD']=='POST'){
  [$k,$err]=simpanPermohonan($_POST,$_FILES);
  echo json_encode($err?['ok'=>false,'msg'=>$err]:['ok'=>true,'kode'=>$k]);exit;}
 if(isset($_GET['kode'])){
  $c=q("SELECT status,catatan,hasil FROM permohonan WHERE kode=?",[trim($_GET['kode'])])->fetch();
  echo json_encode($c?['ok'=>true,'status'=>$c['status'],'catatan'=>$c['catatan'],'hasil'=>$c['hasil']?$GLOBALS['base'].'/uploads/'.$c['hasil']:null]:['ok'=>false,'msg'=>'Kode tidak ditemukan.']);exit;}
}
