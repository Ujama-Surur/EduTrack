<?php require_once '../includes/header.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$students = $pdo->query("SELECT * FROM students ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>Students</h2>
    <a href="add.php" class="btn btn-primary">+ Add Student</a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<?php if (empty($students)): ?>
    <div class="card" style="text-align:center; color:#888; padding:40px;">
        No students found. <a href="add.php">Add the first one</a>.
    </div>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Enrolled</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $i => $s): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($s['full_name']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td><?= htmlspecialchars($s['phone'] ?? '—') ?></td>
            <td><?= date('d M Y', strtotime($s['created_at'])) ?></td>
            <td>
                <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-primary">Edit</a>
                <a href="delete.php?id=<?= $s['id'] ?>"
                   class="btn btn-danger"
                   onclick="return confirm('Delete this student?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>