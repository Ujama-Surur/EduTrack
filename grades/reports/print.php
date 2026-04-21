<?php include('../config/db.php'); ?>

<?php
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

<h2>Student Report</h2>
<p>Name: <?= $student['full_name'] ?></p>

<table border="1" width="100%">
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

<script>
    window.print();
</script>