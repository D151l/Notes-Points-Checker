<!DOCTYPE html>
<html lang="de">

<?php
session_start();

if (!isset($_SESSION['userDisplayName'])) {
    header("Location: index.php?not-logind=1");
    exit();
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=notesPointsChecker', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Verbindungsfehler: ' . $e->getMessage());
}

$statementt = $pdo->prepare("SELECT * FROM performance_courses WHERE userid = ?");
$statementt->execute([$_SESSION['userid']]);

if ($statementt->rowCount() < 1) {
    header("Location: grades.php");
    exit();
}

// Holen von Leistungskursen
$performanceCourses = getPerformanceCourses($pdo, $_SESSION['userid']);


// Verarbeiten des Formulars
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($performanceCourses as $row) {
        if (isset($_POST[$row["subjectId"]])) {

            $sql = "UPDATE exams SET examsGrade = :examsGrade WHERE userid = :userid AND subjectId = :subjectId";

            $statement = $pdo->prepare($sql);

            $statement->bindParam(':examsGrade', $_POST[$row["subjectId"]]);
            $statement->bindParam(':userid', $_SESSION['userid']);
            $statement->bindParam(':subjectId', $row["subjectId"]);

            $statement->execute();
        }
    }

    header("Location: grades.php");
    exit();
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Noten eintragen</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/icon.png" type="image/png">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <?php include("./includes/Sidebar.php"); ?>
        <div class="content">
            <h1>Du willst deine Noten bearbeiten?</h1>
            <p>Hier kannst du deine Daten bearbeiten.
            </p>
            <?php
            // Überprüfen, ob ausreichend Leistungskurse eingetragen wurden
            if (count($performanceCourses) < 5) {
                echo 'Du musst deine Leistungskurse eintragen, bevor du Prüfungen eintragen kannst. <br><br>';
                echo '<a class="button" href="performance-courses.php">Leistungskurse eintragen</a>';
            } else {
                echo '
                    <center>
                        <div class="login-form">
                            <h2>Prüfungen</h2>
                            <form action="edit-exams.php" method="post">
                            ';
                            // Anzeigen von Formularelementen
                            displayFormElements($pdo, $performanceCourses);

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
function displayFormElements($pdo, $performanceCourses)
{
    foreach ($performanceCourses as $row) {
        $statement = $pdo->prepare("SELECT *
        FROM exams 
        INNER JOIN subjects ON exams.subjectId=subjects.id
        WHERE userid = ? AND subjectId = ?");
        $statement->execute([$_SESSION['userid'], $row["subjectId"]]);
        $grade = $statement->fetch();

        echo $grade["displayName"];
        echo '<input type="number" id="' . $row["subjectId"] . '" name="' . $row["subjectId"] . '" min="0" max="15" value=' . $grade['examsGrade'] . ' required>';
    }
}

// Schließe die SQL verbindung
$pdo = null;
?>