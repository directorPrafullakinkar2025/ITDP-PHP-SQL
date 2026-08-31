<?php
require_once __DIR__ . '/auth.php';
require_login();

$base = preg_replace(
    '#/(auth|admin|records|work|operational|files|templates|institution)$#',
    '',
    str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']))
);

if ($base === '/') {
    $base = '';
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ITDP Governance Platform</title>
    <link rel="stylesheet" href="<?= $base ?>/assets/style.css">
</head>

<body>
    <nav>
        <div><b>ITDP</b><span class="nav-sub">Integrated Tribal Development Platform</span></div>
        <div class="nav-user"><?= e(user()['name']) ?> · <span class="role"><?= e(role_label(user()['role'] ?? '')) ?></span>
            · <a href="<?= $base ?>/auth/logout.php">Logout</a></div>
    </nav>
    <div class="layout">
        <aside>
            <a class="side active" href="<?= $base ?>/dashboard.php">🏠 Dashboard</a>
            <a class="side" href="<?= $base ?>/notifications.php">🔔 Alerts</a>
            <a class="side" href="<?= $base ?>/records/list.php">📁 Records</a>
            <a class="side" href="<?= $base ?>/work/list.php">✅ Work & Tasks</a>
            <a class="side" href="<?= $base ?>/work/orders.php">📢 Orders & Circulars</a>
            <a class="side" href="<?= $base ?>/operational/list.php">📊 Daily Reports</a>
            <?php if (user()['institution_type'] === 'ashram_shala' || is_admin() || is_sub_officer()): ?><a class="side"
                    href="<?= $base ?>/institution/students.php">🎓 Students & Presenty</a><?php endif; ?>
            <?php if (user()['institution_type'] === 'wastigruh' || is_admin() || is_sub_officer()): ?><a class="side"
                    href="<?= $base ?>/institution/wastigruh.php">🏠 Wastigruh Status</a><?php endif; ?>
            <a class="side" href="<?= $base ?>/files/list.php">📎 Files</a>
            <?php if (is_admin()): ?>
                <div class="side-label">ADMINISTRATION</div>
                <a class="side" href="<?= $base ?>/admin/users.php">👥 Users & Approvals</a>
                <a class="side" href="<?= $base ?>/admin/operations.php">⚙ Operations</a>
                <a class="side" href="<?= $base ?>/admin/activity.php">🕘 Activity Log</a>
                <a class="side" href="<?= $base ?>/templates/list.php">📄 Format Library</a>
            <?php elseif (is_sub_officer()): ?>
                <div class="side-label">SUPERVISION</div>
                <a class="side" href="<?= $base ?>/operational/list.php">🔎 Verify Reports</a>
                <a class="side" href="<?= $base ?>/institution/students.php">🎓 Student Monitoring</a>
                <a class="side" href="<?= $base ?>/institution/wastigruh.php">🏠 Hostel Monitoring</a>
            <?php endif; ?>
        </aside>
        <main>
            <?php foreach (flashes() as $f): ?>
                <div class="alert <?= $f[0] ?>"><?= e($f[1]) ?></div><?php endforeach; ?>