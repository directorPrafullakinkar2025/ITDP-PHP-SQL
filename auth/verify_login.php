<?php require_once '../includes/auth.php'; verify_csrf();
$email=strtolower(trim($_POST['email']??'')); $pw=$_POST['password']??''; $lt=$_POST['login_type']??'';
$map=['main_officer'=>['admin','super_admin'],'sub_officer'=>['sub_officer'],'clerk'=>['authorized_staff','staff'],'ashram_shala'=>['school_superintendent','teacher','authorized_staff','staff'],'wastigruh'=>['hostel_superintendent','authorized_staff','staff']];
$s=mysqli_prepare($conn,"SELECT * FROM users WHERE email=? LIMIT 1"); mysqli_stmt_bind_param($s,'s',$email); mysqli_stmt_execute($s); $u=mysqli_fetch_assoc(mysqli_stmt_get_result($s));
$ok=$u && $u['status']==='active' && password_verify($pw,$u['password']);
// Main Officer is the top-level administrator; approve legacy pending admin accounts automatically.
if($ok && in_array($u['role'],['admin','super_admin'],true)){
  if($u['approval_status']!=='approved'){
    mysqli_query($conn,"UPDATE users SET approval_status='approved', status='active', approved_at=NOW() WHERE id=".(int)$u['id']);
    $u['approval_status']='approved'; $u['status']='active';
  }
}
if($ok && $u['approval_status']==='approved'){
  if(!isset($map[$lt]) || !in_array($u['role'],$map[$lt],true)) $ok=false;
  if(in_array($lt,['ashram_shala','wastigruh','clerk'],true) && !in_array($u['institution_type'],['ashram_shala','wastigruh'],true)) $ok=false;
  if($lt==='ashram_shala' && $u['institution_type']!=='ashram_shala') $ok=false;
  if($lt==='wastigruh' && $u['institution_type']!=='wastigruh') $ok=false;
}
if(!$ok) redirect('login.php?type='.urlencode($lt).'&error='.urlencode('Invalid credentials, approval status or selected portal.'));
unset($u['password']); session_regenerate_id(true); $_SESSION['user']=$u; mysqli_query($conn,"UPDATE users SET last_login_at=NOW() WHERE id=".(int)$u['id']); log_activity('login','auth','User signed in'); redirect('../dashboard.php');
