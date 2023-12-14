<!DOCTYPE html>
<html lang="de">

<?php
session_start();

// Überprüfen Sie die Anmeldung
if (!isset($_SESSION['userDisplayName'])) {
    header("Location: index.php?not-loggedin=1");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "notesPointsChecker";

// Verbindung zur Datenbank
$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Holen von Leistungskursen
$performanceCourses = getPerformanceCourses($pdo, $_SESSION['userid']);

// Verarbeiten des Formulars
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $count = 0;
    foreach ($performanceCourses as $row) {
        if (isset($_POST[$row["subjectId"]])) {
            $insertStatement = $pdo->prepare("INSERT INTO exams (examsGrade, subjectId, userid) VALUES (?, ?, ?)");
            $result = $insertStatement->execute(array($_POST[$row["subjectId"]], $row["subjectId"], $_SESSION['userid']));

            $count++;
        }
    }

    if ($count > 0) {
        header("Location: grades.php");
        exit();
    }
}

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Prüfungen</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="https://cdn.discordapp.com/attachments/795090945007812640/1182039052288000140/107086.jpg"
        type="image/png">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <?php include("./includes/Sidebar.php"); ?>
        <div class="content">
            <h1>Du möchtest deine Prüfungen eintragen?</h1>
            <p>Hier kannst du deine Daten eintragen.</p>

            <?php
            // Überprüfen, ob ausreichend Leistungskurse eingetragen wurden
            if (count($performanceCourses) < 5) {
                echo 'Du musst deine Prüfungsfächer eintragen, bevor du Prüfungen eintragen kannst. <br><br>';
                echo '<a class="button" href="performance-courses.php">Prüfungsfächer bearbeiten</a>';
            } else {
                echo '
                    <center>
                        <div class="login-form">
                            <h2>Prüfungen</h2>
                            <form action="enter-exams.php" method="post">
                            ';
                // Anzeigen von Formularelementen
                displayFormElements($performanceCourses);

                echo '
                                <button type="submit">Speichern</button>
                            </form>
                        </div>
                    </center>
                ';
            }
            ?>
        </div>
    </div>
</body>

</html>

<?php
// Funktion zum Holen von Leistungskursen
function getPerformanceCourses($pdo, $userId)
{
    $statement = $pdo->prepare('SELECT * FROM performance_courses WHERE userid = ?');
    $statement->execute(array($userId));
    return $statement->fetchAll();
}

// Funktion zum Anzeigen von Formularelementen
function displayFormElements($performanceCourses)
{
    foreach ($performanceCourses as $row) {
        echo $row["subjectId"];
        echo '<input type="number" id="' . $row["subjectId"] . '" name="' . $row["subjectId"] . '" min="0" max="15" required>';
    }
}

// Schließen Sie die SQL-Verbindung
$pdo = null;
?>