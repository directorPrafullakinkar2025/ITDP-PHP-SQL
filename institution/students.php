<?php include '../includes/header.php';
$u = user();
$isScope = !is_admin() && !is_sub_officer();
if ($isScope) {
    $s = mysqli_prepare($conn, "SELECT * FROM students WHERE institution_type=? AND institution_code=? ORDER BY id DESC");
    mysqli_stmt_bind_param($s, 'ss', $u['institution_type'], $u['institution_code']);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
} else {
    $r = mysqli_query($conn, "SELECT * FROM students ORDER BY institution_code,id DESC");
}
?>
<div class="page-head">
    <div>
        <p class="eyebrow">ASHRAM SHALA</p>
        <h1>Student Records & Presenty</h1>
        <p class="muted">Maintain student master records and daily attendance.</p>
    </div>
    <div class="actions"><a class="btn primary" href="student_add.php">+ Add Student</a><a class="btn"
            href="attendance.php">Daily Presenty</a></div>
</div>
<div class="card table-wrap">
    <table>
        <tr>
            <th>Student</th>
            <th>Admission</th>
            <th>Class</th>
            <th>Gender</th>
            <th>Institution</th>
            <th>Status</th>
        </tr><?php while ($x = mysqli_fetch_assoc($r)): ?>
            <tr>
                <td><b><?= e($x['student_name']) ?></b><br><small><?= e($x['student_uid']) ?></small></td>
                <td><?= e($x['admission_no']) ?></td>
                <td><?= e($x['class_name']) ?></td>
                <td><?= e($x['gender']) ?></td>
                <td><?= e($x['institution_code']) ?></td>
                <td><span class="pill <?= e($x['status']) ?>"><?= e($x['status']) ?></span></td>
            </tr><?php endwhile; ?>
    </table>
</div><?php include '../includes/footer.php'; ?>