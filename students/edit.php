<?php require_once '../includes/header.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo '<div class="alert alert-danger">Student not found.</div>';
    require_once '../includes/footer.php';
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');

    if ($full_name === '' || $email === '') {
        $error = 'Full name and email are required.';
    } else {
        $stmt = $pdo->prepare("UPDATE students SET full_name=?, email=?, phone=? WHERE id=?");
        $stmt->execute([$full_name, $email, $phone, $id]);
        header("Location: index.php?msg=Student+updated+successfully");
        exit;
    }
}
?>

<h2>Edit Student</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card" style="max-width:500px;">
    <form method="POST">
        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? $student['full_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $student['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? $student['phone']) ?>">
        </div>
        <button type="submit" class="btn btn-success">Update Student</button>
        <a href="index.php" class="btn" style="background:#aaa;color:white;">Cancel</a>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>