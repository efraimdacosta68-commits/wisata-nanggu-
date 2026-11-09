<?php require '../config.php';$err='';
if($_SERVER['REQUEST_METHOD']=='POST'){chk();$u=$_POST['u'];$p=$_POST['p'];
 if(!q("SELECT COUNT(*) c FROM admin")->fetch()['c']){q("INSERT INTO admin(username,password) VALUES(?,?)",[$u,password_hash($p,PASSWORD_DEFAULT)]);} // login pertama = membuat akun admin
 $a=q("SELECT * FROM admin WHERE username=?",[$u])->fetch();
 if($a&&password_verify($p,$a['password'])){session_regenerate_id(true);$_SESSION['adm']=$a['id'];header('Location: index.php');exit;}$err='Username/password salah';}?>
<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width,initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="<?=$base?>/assets/ui.css" rel="stylesheet"></head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh"><form method="post" class="card card-body shadow mx-auto" style="width:340px"><h5 class="text-center">Login Admin</h5>
<?php if($err):?><div class="alert alert-danger py-1"><?=$err?></div><?php endif?><input type="hidden" name="t" value="<?=csrf()?>">
<input name="u" class="form-control mb-2" placeholder="Username" required><input type="password" name="p" class="form-control mb-3" placeholder="Password" required><button class="btn btn-success">Masuk</button></form></body></html>
