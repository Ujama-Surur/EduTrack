<?php require_once '../includes/header.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
$stmt->execute([$id]);
$subject = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subject) {
    echo '<div class="alert alert-danger">Subject not found.</div>';
    require_once '../includes/footer.php';
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_name = trim($_POST['subject_name'] ?? '');
    $code         = trim($_POST['code'] ?? '');
    $description  = trim($_POST['description'] ?? '');

    if ($subject_name === '') {
        $error = 'Subject name is required.';
    } else {
        $stmt = $pdo->prepare("UPDATE subjects SET subject_name=?, code=?, description=? WHERE id=?");
        $stmt->execute([$subject_name, $code, $description, $id]);
        header("Location: index.php?msg=Subject+updated+successfully");
        exit;
    }
}
?>

<h2>Edit Subject</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width:500px;">
    <form method="POST">
        <div class="form-group">
            <label>Subject Name *</label>
            <input type="text" name="subject_name" value="<?= htmlspecialchars($_POST['subject_name'] ?? $subject['subject_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Subject Code</label>
            <input type="text" name="code" value="<?= htmlspecialchars($_POST['code'] ?? $subject['code']) ?>">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?= htmlspecialchars($_POST['description'] ?? $subject['description']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Subject</button>
        <a href="index.php" class="btn" style="background:#aaa;color:white;">Cancel</a>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>