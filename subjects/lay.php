<?php require_once '../includes/header.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$subjects = $pdo->query("SELECT * FROM subjects ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h2>Subjects</h2>
    <a href="add.php" class="btn btn-success">+ Add Subject</a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<?php if (empty($subjects)): ?>
    <div class="card" style="text-align:center; color:#888; padding:40px;">
        No subjects found. <a href="add.php">Add the first one</a>.
    </div>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Subject Name</th>
            <th>Code</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($subjects as $i => $sub): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($sub['subject_name']) ?></td>
            <td><?= htmlspecialchars($sub['code'] ?? '—') ?></td>
            <td><?= htmlspecialchars($sub['description'] ?? '—') ?></td>
            <td>
                <a href="edit.php?id=<?= $sub['id'] ?>" class="btn btn-primary">Edit</a>
                <a href="delete.php?id=<?= $sub['id'] ?>"
                   class="btn btn-danger"
                   onclick="return confirm('Delete this subject?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>