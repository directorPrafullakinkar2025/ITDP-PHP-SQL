<?php require_once '../includes/auth.php'; verify_csrf();
$portal=$_POST['portal_type']??''; $allowed=['main_officer','sub_officer','clerk','ashram_shala','wastigruh'];
if(!in_array($portal,$allowed,true)) redirect('register.php?error='.urlencode('Please select a valid registration type.'));
$name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??'')); $pw=$_POST['password']??'';
if(strlen($name)<2||strlen($pw)<8||!filter_var($email,FILTER_VALIDATE_EMAIL)) redirect('register.php?type='.urlencode($portal).'&error='.urlencode('Please enter valid registration details.'));
$role='staff'; $it=''; $code='';
$approval='pending';
if($portal==='main_officer'){
  if(($_POST['registration_key']??'')!=='ITDP-MAIN-2026') redirect('register.php?type=main_officer&error='.urlencode('Invalid officer registration key.'));
  $role='super_admin';
  // Main Officer is the top-level administrator and must be able to sign in immediately.
  $approval='approved';
} elseif($portal==='sub_officer') { $role='sub_officer'; $code=strtoupper(trim($_POST['office_code']??'')); if($code==='') redirect('register.php?type=sub_officer&error='.urlencode('Office code is required.')); }
else {
  $it=$_POST['institution_type']??''; $code=strtoupper(trim($_POST['institution_code']??''));
  if(!in_array($it,['ashram_shala','wastigruh'],true)||$code==='') redirect('register.php?type='.urlencode($portal).'&error='.urlencode('Institution and code are required.'));
  $role=$portal==='ashram_shala'?'school_superintendent':($portal==='wastigruh'?'hostel_superintendent':'authorized_staff');
}
$hash=password_hash($pw,PASSWORD_DEFAULT); $status='active';
$s=mysqli_prepare($conn,"INSERT INTO users(name,email,password,role,institution_type,institution_code,status,approval_status) VALUES(?,?,?,?,?,?,?,?)");
mysqli_stmt_bind_param($s,'ssssssss',$name,$email,$hash,$role,$it,$code,$status,$approval);
if(!mysqli_stmt_execute($s)) redirect('register.php?type='.urlencode($portal).'&error='.urlencode('Email already exists or registration could not be saved.'));
mysqli_query($conn,"INSERT INTO alerts(type,title,message,severity,institution_type,institution_code) VALUES('approval','New registration','A new account is waiting for administrator approval.','info','".mysqli_real_escape_string($conn,$it)."','".mysqli_real_escape_string($conn,$code)."')");
redirect('login.php?error='.urlencode($meta='Registration submitted successfully. Please wait for administrator approval.'));
