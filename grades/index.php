<?php include('../config/db.php'); ?>
<?php include('../includes/header.php'); ?>

<h2>Grades</h2>

<form method="GET">
    <select name="student_id">
        <option value="">All Students</option>
        <?php
        $students = $conn->query("SELECT * FROM students");
        while($s = $students->fetch_assoc()):
        ?>
        <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Filter</button>
</form>

<a href="add.php">+ Add Grade</a>

<table border="1">
<tr>
    <th>Student</th>
    <th>Subject</th>
    <th>Grade</th>
    <th>Semester</th>
    <th>Year</th>
</tr>

<?php
$where = "";
if (!empty($_GET['student_id'])) {
    $id = $_GET['student_id'];
    $where = "WHERE g.student_id = $id";
}

$result = $conn->query("
    SELECT g.*, s.full_name, sub.title 
    FROM grades g
    JOIN students s ON g.student_id = s.id
    JOIN subjects sub ON g.subject_id = sub.id
    $where
");

while($row = $result->fetch_assoc()):
?>

<tr>
    <td><?= $row['full_name'] ?></td>
    <td><?= $row['title'] ?></td>
    <td><?= $row['grade'] ?></td>
    <td><?= $row['semester'] ?></td>
    <td><?= $row['year'] ?></td>
</tr>

<?php endwhile; ?>

</table>

<?php include('../includes/footer.php'); ?>