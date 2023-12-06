<!DOCTYPE html>
<html lang="de">

<?php
session_start();

if (!isset($_SESSION['userDisplayName'])) {
    header("Location: index.php?not-logind=1");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "notesPointsChecker";

// Verbinde zu Datenbank
$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Noten</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <?php include("./includes/Sidebar.php"); ?>
        <div class="content">
            <h1>Übersicht deiner Noten</h1>
            <p>Hier hast du eine Übersicht all deiner Noten!</p>

            <a class="button" href="performance-courses.php">Leistungskurse bearbeiten</a>

            <?php
            $semesterStatement = $pdo->prepare("SELECT semester FROM grades WHERE userid = ? GROUP BY semester;");
            $semesterStatement->execute(array($_SESSION['userid']));

            if ($semesterStatement->rowCount() < 4) {
                echo '
                <a class="button" href="enter-grades.php">Noten eintragen</a>
                <br>
                <p>Du musst in allen vier Semestern Noten eintragen, um die Punkte zu berechnen.</p>
                ';
            } else {
                echo '
                    <p>Du hast mit deinen momentanen Noten 756 Punkte und hast damit <span class="text-green">bestanden</span>!</p>
                    <p>Du hast mit deinen momentanen Noten 256 Punkte und hast damit <span class="text-red">nicht bestanden</span>!</p>
                ';
            }
            ?>

            <table>
                <thead>
                    <tr>
                        <th>Fach</th>
                        <?php
                        for ($i = 1; $i <= 4; $i++) {
                            echo "<th>Semester $i</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $subjectSql = "SELECT * FROM subjects";
                    foreach ($pdo->query($subjectSql) as $row) {

                        $lk = "";
                        $semesterStatement = $pdo->prepare('SELECT * FROM performance_courses WHERE userid = ? AND subjectId = ?');
                        $semesterStatement->execute(array($_SESSION['userid'], $row["id"]));
                        if ($semesterStatement->rowCount() > 0) {
                            $lk = "(Leistungs Kurs)";
                        }


                        echo '<tr>
                                <td>' . $row["displayName"] . ' ' . $lk . '</td>';

                        for ($semester = 1; $semester <= 4; $semester++) {
                            echo '<td>';
                            displayGrade($pdo, $row["id"], $semester, $_SESSION['userid']);
                            echo '</td>';
                        }

                        echo '</tr>';
                    }
                    ?>
                    <tr>
                        <td></td>
                        <?php
                        for ($i = 1; $i <= 4; $i++) {
                            echo '<td><a class="button" href="edit-grades.php?semester=' . $i . '">Noten bearbeiten</a></td>';
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>

<?php
function displayGrade($pdo, $subjectId, $semester, $userId)
{
    $statement = $pdo->prepare("SELECT * FROM grades WHERE semester = ? AND subjectId = ? AND userid = ?");
    $statement->execute([$semester, $subjectId, $userId]);

    if ($statement->rowCount() > 0) {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        echo $result['grade'];
    } else {
        echo 'Keine Daten';
    }
}

// Schließe die SQL verbindung
$pdo = null;
?>