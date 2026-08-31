<?php require_once '../includes/auth.php';
if (user()) {
    redirect('../dashboard.php');
}
$err = $_GET['error'] ?? '';
$type = $_GET['type'] ?? ''; ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ITDP Login Portal</title>
    <link rel="stylesheet" href="../assets/style.css?v=5">
</head>

<body class="auth-bg">
    <div class="portal-wrap">
        <div class="portal-head">
            <div class="brand-mark">ITDP</div>
            <div>
                <h1>Login Portal</h1>
                <p>Select your ITDP workspace</p>
            </div><a class="btn" href="register.php">Create account</a>
        </div><?php if ($err): ?>
            <div class="alert danger"><?= e($err) ?></div><?php endif; ?>
        <div class="portal-grid login-grid">
            <?php $items = [['main_officer', '🏛️', 'Main Officer', 'District administration'], ['sub_officer', '👮', 'Sub Officer', 'Supervision & verification'], ['clerk', '🧑‍💼', 'Clerk / Data Entry', 'Daily data entry'], ['ashram_shala', '🎓', 'Ashram Shala', 'Student & presenty'], ['wastigruh', '🏠', 'Wastigruh', 'Hostel monitoring']];
            foreach ($items as $x): ?><a
                    class="portal-card login-choice" href="login.php?type=<?= $x[0] ?>"><span><?= $x[1] ?></span>
                    <h3><?= $x[2] ?></h3>
                    <p><?= $x[3] ?></p><b>Login →</b>
                </a><?php endforeach; ?>
        </div><?php if ($type): ?>
            <div class="card login-form-card">
                <div class="selected-login"><span><?= e($items[array_search($type, array_column($items, 0))][1]) ?></span>
                    <div>
                        <strong><?= e($items[array_search($type, array_column($items, 0))][2]) ?></strong><small><?= e($items[array_search($type, array_column($items, 0))][3]) ?></small>
                    </div>
                </div>
                <form method="post" action="verify_login.php"><?= csrf_field() ?><input type="hidden" name="login_type"
                        value="<?= e($type) ?>"><label>Email</label><input name="email" type="email" autocomplete="username"
                        required><label>Password</label><input name="password" type="password"
                        autocomplete="current-password" required><button class="wide">Secure Sign in</button></form>
                <p class="center">Need an account? <a href="register.php?type=<?= e($type) ?>">Register for this portal</a>
                </p>
            </div><?php endif; ?>
    </div>
</body>

</html>