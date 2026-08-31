<?php
if(isset($_GET['type'])) { require __DIR__.'/register_form.php'; exit; }
require_once '../includes/auth.php'; if(user()){redirect('../dashboard.php');} $err=$_GET['error']??''; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ITDP Registration Portal</title><link rel="stylesheet" href="../assets/style.css?v=5"></head><body class="auth-bg">
<div class="portal-wrap">
  <div class="portal-head"><div class="brand-mark">ITDP</div><div><h1>Registration Portal</h1><p>Choose your ITDP access category</p></div><a class="btn" href="login.php">Already have login</a></div>
  <?php if($err):?><div class="alert danger"><?=e($err)?></div><?php endif;?>
  <div class="portal-grid">
    <a class="portal-card officer" href="register.php?type=main_officer"><span>🏛️</span><h3>Main Officer</h3><p>District / ITDP administration, approvals and overall monitoring.</p><b>Register as Main Officer →</b></a>
    <a class="portal-card officer" href="register.php?type=sub_officer"><span>👮</span><h3>Sub Officer</h3><p>Supervision, verification and institution-level monitoring.</p><b>Register as Sub Officer →</b></a>
    <a class="portal-card" href="register.php?type=clerk"><span>🧑‍💼</span><h3>Clerk / Data Entry</h3><p>Daily entries, records, attendance and Wastigruh data.</p><b>Register as Clerk →</b></a>
    <a class="portal-card" href="register.php?type=ashram_shala"><span>🎓</span><h3>Ashram Shala</h3><p>School login, student records and daily presenty.</p><b>Register Ashram Shala →</b></a>
    <a class="portal-card" href="register.php?type=wastigruh"><span>🏠</span><h3>Wastigruh</h3><p>Hostel login, residents, meals, clothes, sports and facility status.</p><b>Register Wastigruh →</b></a>
  </div>
</div>
</body></html>
