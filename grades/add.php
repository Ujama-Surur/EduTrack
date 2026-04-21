<?php include('../config/db.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $grade = $_POST['grade'];
    $semester = $_POST['semester'];
    $year = $_POST['year'];

    $stmt = $conn->prepare("INSERT INTO grades (student_id, subject_id, grade, semester, year) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $student_id, $subject_id, $grade, $semester, $year);
    $stmt->execute();

    header("Location: index.php");
}
?>

<h2>Add Grade</h2>

<form method="POST">

    Student:
    <select name="student_id" required>
        <?php
        $students = $conn->query("SELECT * FROM students");
        while($s = $students->fetch_assoc()):
        ?>
        <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    Subject:
    <select name="subject_id" required>
        <?php
        $subjects = $conn->query("SELECT * FROM subjects");
        while($sub = $subjects->fetch_assoc()):
        ?>
        <option value="<?= $sub['id'] ?>"><?= $sub['title'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    Grade: <input type="number" step="0.01" name="grade" required><br><br>

    Semester:
    <select name="semester">
        <option>Semester 1</option>
        <option>Semester 2</option>
    </select><br><br>

    Year: <input type="number" name="year" required><br><br>

    <button type="submit">Save</button>
</form>