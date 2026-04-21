<?php include('../config/db.php'); ?>
<?php include('../includes/header.php'); ?>

<h2>Reports</h2>

<form method="GET">
    <select name="student_id" required>
        <option value="">Select Student</option>
        <?php
        $students = $conn->query("SELECT * FROM students");
        while($s = $students->fetch_assoc()):
        ?>
        <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Generate</button>
</form>

<?php
if (!empty($_GET['student_id'])):

$id = $_GET['student_id'];

$student = $conn->query("SELECT * FROM students WHERE id=$id")->fetch_assoc();

$grades = $conn->query("
    SELECT g.*, sub.title, sub.units
    FROM grades g
    JOIN subjects sub ON g.subject_id = sub.id
    WHERE g.student_id = $id
");

$total = 0;
$units = 0;
?>

<h3><?= $student['full_name'] ?> Report</h3>

<table border="1">
<tr>
    <th>Subject</th>
    <th>Units</th>
    <th>Grade</th>
</tr>

<?php while($g = $grades->fetch_assoc()): 
    $total += $g['grade'] * $g['units'];
    $units += $g['units'];
?>

<tr>
    <td><?= $g['title'] ?></td>
    <td><?= $g['units'] ?></td>
    <td><?= $g['grade'] ?></td>
</tr>

<?php endwhile; ?>

</table>

<?php
$gpa = ($units > 0) ? $total / $units : 0;
?>

<h3>GPA: <?= number_format($gpa, 2) ?></h3>

<a href="print.php?student_id=<?= $id ?>" target="_blank">Print Report</a>

<?php endif; ?>

<?php include('../includes/footer.php'); ?>