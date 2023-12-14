<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "notesPointsChecker";

// Verbinde zu Datenbank
$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$semester = $_POST['semester'];

// Alle Fächer aus der Datenbank holen
$sql = "SELECT * FROM subjects";
foreach ($pdo->query($sql) as $row) {

    $points = $_POST[$row["id"] . '-grade'];

    // Die Note des Benutzers aus der Datenbank holen
    $statement = $pdo->prepare("INSERT INTO grades (grade, subjectId, semester, userid) VALUES (?, ?, ?, ?)");
    $statement->execute(array($points, $row["id"], $semester, $_SESSION['userid']));


}

// Schließe die SQL verbindung
$pdo = null;

header("Location: grades.php");
exit();
?>