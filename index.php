<?php
require('config/db.php');

$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$activeStudents = $pdo->query("SELECT COUNT(*) FROM students WHERE status = 'active'")->fetchColumn();
$totalSubjects = $pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();
$totalGrades = $pdo->query("SELECT COUNT(*) FROM grades")->fetchColumn();
$avgGrade = $pdo->query("SELECT ROUND(AVG(score), 1) FROM grades")->fetchColumn();

$recentStudents = $pdo->query("
    SELECT student_no, name, course, status 
    FROM students 
    ORDER BY id DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$gradeDistribution = $pdo->query("
    SELECT 
        SUM(CASE WHEN score >= 90 THEN 1 ELSE 0 END) AS a_count,
        SUM(CASE WHEN score >= 80 AND score < 90 THEN 1 ELSE 0 END) AS b_count,
        SUM(CASE WHEN score >= 70 AND score < 80 THEN 1 ELSE 0 END) AS c_count,
        SUM(CASE WHEN score < 70 THEN 1 ELSE 0 END) AS d_count,
        COUNT(*) AS total
    FROM grades
")->fetch(PDO::FETCH_ASSOC);

$total = $gradeDistribution['total'] > 0 ? $gradeDistribution['total'] : 1;
$aPct = round($gradeDistribution['a_count'] / $total * 100);
$bPct = round($gradeDistribution['b_count'] / $total * 100);
$cPct = round($gradeDistribution['c_count'] / $total * 100);
$dPct = round($gradeDistribution['d_count'] / $total * 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EduTrack – Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>

<!-- NAV -->
<nav>
  <a href="index.php" class="logo">
    <div class="logo-icon"></div>
    EduTrack
  </a>
  <div class="nav-links">
    <a href="index.php" class="active">Dashboard</a>
    <a href="students.php">Students</a>
    <a href="subjects.php">Subjects</a>
    <a href="grades.php">Grades</a>
    <a href="reports.php">Reports</a>
  </div>
  <div class="nav-badge">XAMPP / MySQL</div>
</nav>

<!-- MAIN -->
<main>
  <div class="page-header">
    <div>
      <h1>Dashboard</h1>
      <p>Overview of your student management system</p>
    </div>
    <a href="add_student.php" class="btn-primary">+ Add Student</a>
  </div>

  <!-- STAT CARDS -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-label">Total Students</div>
      <div class="stat-value"><?= $totalStudents ?></div>
      <div class="stat-sub"><?= $activeStudents ?> currently active</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Subjects Offered</div>
      <div class="stat-value"><?= $totalSubjects ?></div>
      <div class="stat-sub">Across all departments</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Grade Records</div>
      <div class="stat-value"><?= $totalGrades ?></div>
      <div class="stat-sub">Total enrolled grades</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Average Grade</div>
      <div class="stat-value"><?= $avgGrade ?: '—' ?></div>
      <div class="stat-sub">Across all subjects</div>
    </div>
  </div>

  <!-- BOTTOM GRID -->
  <div class="bottom-grid">

    <!-- Recent Students -->
    <div class="card">
      <div class="card-header">
        <h2>Recent Students</h2>
        <a href="students.php" class="btn-ghost">View All</a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Student No.</th>
            <th>Name</th>
            <th>Course</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentStudents)): ?>
            <tr><td colspan="4">No students found.</td></tr>
          <?php else: ?>
            <?php foreach ($recentStudents as $s): ?>
              <?php
                $status = strtolower($s['status']);
                $badgeClass = match($status) {
                  'active'    => 'badge-active',
                  'graduated' => 'badge-graduated',
                  default     => 'badge-inactive'
                };
              ?>
              <tr>
                <td><?= htmlspecialchars($s['student_no']) ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= htmlspecialchars($s['course']) ?></td>
                <td><span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Grade Distribution -->
    <div class="card">
      <div class="card-header">
        <h2>Grade Distribution</h2>
        <a href="grades.php" class="btn-ghost">View Grades</a>
      </div>
      <div class="grade-list">
        <div class="grade-row">
          <div class="grade-row-top"><span>A (90–100)</span><span>(<?= $aPct ?>%)</span></div>
          <div class="grade-bar-bg"><div class="grade-bar-fill fill-a" style="width:<?= $aPct ?>%"></div></div>
        </div>
        <div class="grade-row">
          <div class="grade-row-top"><span>B (80–89)</span><span>(<?= $bPct ?>%)</span></div>
          <div class="grade-bar-bg"><div class="grade-bar-fill fill-b" style="width:<?= $bPct ?>%"></div></div>
        </div>
        <div class="grade-row">
          <div class="grade-row-top"><span>C (70–79)</span><span>(<?= $cPct ?>%)</span></div>
          <div class="grade-bar-bg"><div class="grade-bar-fill fill-c" style="width:<?= $cPct ?>%"></div></div>
        </div>
        <div class="grade-row">
          <div class="grade-row-top"><span>D (&lt;70)</span><span>(<?= $dPct ?>%)</span></div>
          <div class="grade-bar-bg"><div class="grade-bar-fill fill-d" style="width:<?= $dPct ?>%"></div></div>
        </div>
      </div>
    </div>

  </div>
</main>

</body>
</html>