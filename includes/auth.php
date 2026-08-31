<?php
if(session_status()===PHP_SESSION_NONE){session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax']);session_start();}
require_once __DIR__.'/../config/db.php';
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function user(){return $_SESSION['user']??null;}
function base_url(){return preg_replace('#/(auth|admin|records|work|operational|files|templates)(/.*)?$#','',str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])));} function require_login(){if(!user()){header('Location: '.(base_url()?:'').'/auth/login.php');exit;}}

function role_label($role){return ['super_admin'=>'Main Officer','admin'=>'Main Officer','sub_officer'=>'Sub Officer','school_superintendent'=>'Ashram Shala Superintendent','hostel_superintendent'=>'Wastigruh Superintendent','teacher'=>'Ashram Shala Teacher','authorized_staff'=>'Clerk / Data Entry','staff'=>'Staff'][$role]??ucwords(str_replace('_',' ',$role));}
function is_admin(){return in_array(user()['role']??'', ['super_admin','admin'],true);}
function is_sub_officer(){return (user()['role']??'')==='sub_officer';}
function is_clerk(){return in_array(user()['role']??'', ['staff','authorized_staff'],true);}
function is_institution_user(){return !is_admin() && !is_sub_officer();}
function can_manage_institution(){return is_admin() || is_sub_officer() || is_institution_user();}
function require_role($roles){require_login(); if(!in_array(user()['role']??'',(array)$roles,true)){http_response_code(403);exit('Forbidden');}}
function require_admin(){require_login();if(!is_admin()){http_response_code(403);exit('Forbidden');}}
function require_supervision(){require_login();if(!is_admin()&&!is_sub_officer()){http_response_code(403);exit('Forbidden');}}
function csrf_token(){if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function csrf_field(){return '<input type="hidden" name="csrf" value="'.e(csrf_token()).'">';}
function verify_csrf(){if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(419);exit('Invalid CSRF token. Refresh and try again.');}}
function flash($type,$msg){$_SESSION['flash'][]=[$type,$msg];}
function flashes(){ $x=$_SESSION['flash']??[];unset($_SESSION['flash']);return $x; }
function scope_where($alias=''){
  $u=user();$p=$alias?$alias.'.':'';
  if(is_admin())return ['1=1',[],''];
  return ["{$p}institution_type=? AND {$p}institution_code=?",[$u['institution_type'],$u['institution_code']],'ss'];
}
function log_activity($action,$module,$summary,$record_id=null){
  global $conn;$s=mysqli_prepare($conn,"INSERT INTO activities(user_id,action,module,record_id,summary) VALUES(?,?,?,?,?)");
  $uid=(int)(user()['id']??0);mysqli_stmt_bind_param($s,'issis',$uid,$action,$module,$record_id,$summary);mysqli_stmt_execute($s);
}
function redirect($url){header("Location: $url");exit;}
function today(){return date('Y-m-d');}
