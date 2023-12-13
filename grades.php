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
    <link rel="icon" href="https://cdn.discordapp.com/attachments/795090945007812640/1182039052288000140/107086.jpg" type="image/png">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <?php include("./includes/Sidebar.php"); ?>
        <div class="content">
            <h1>Übersicht deiner Noten</h1>
            <p>Hier hast du eine Übersicht all deiner Noten!</p>

            <a class="button" href="performance-courses.php">Prüfungsfächer bearbeiten</a>

            <?php
            $examStatement = $pdo->prepare('SELECT * FROM exams WHERE userid = ?');
            $examStatement->execute(array($_SESSION['userid']));
            if ($examStatement->rowCount() > 4) {

            } else {
                echo '
                <a class="button" href="enter-exams.php">Prüfungen eintragen</a>
                <br>
                <p>Du musst in allen fünf Prüfungen Noten eintragen, um die Punkte zu berechnen.</p>
                ';
            }
            ?>

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
                echo '<br><br>'. calculatePoints($pdo, $_SESSION['userid']) .'<br><br>';
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
                        <th>Prüfungen</th>
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

                        $examStatement = $pdo->prepare('SELECT * FROM exams WHERE userid = ? AND subjectId = ? LIMIT 1');
                        $examStatement->execute(array($_SESSION['userid'], $row["id"]));
                        $result = $examStatement->fetch(PDO::FETCH_ASSOC);

                        if ($examStatement->rowCount() > 0) {
                            echo '<td>Punkte: '. $result['examsGrade'] .'</td>';
                        } else {
                            echo '<td></td>';
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
                        <td><a class="button" href="edit-exams.php">Prüfungen bearbeiten</a></td>
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

function calculatePoints($pdo, $userId) 
{
    $statementt = $pdo->prepare("SELECT * FROM grades WHERE userid = ? AND grade < 5");
    $statementt->execute([$userId]);

    if ($statementt->rowCount() > 7) {
        return "Du hast mehr als 7 Unterkurse und hast damit <span class='text-red'>nicht bestanden</span>!";
    }

    $gradesStatement = $pdo->prepare("SELECT * FROM grades WHERE userid = ?");
    $gradesStatement ->execute([$userId]);

    $points = 0;
    $lowerCourses = 0;
    while ($row = $gradesStatement->fetch()) {
        $points = $points + $row['grade'];

        if ($row['grade'] == 0) {
            return 'Du hast 0 Punkte in '. $row['subjectId'] .' und hast damit <span class="text-red">nicht bestanden</span>!';
        }

        $performanceCoursesStatement = $pdo->prepare("SELECT * FROM performance_courses WHERE userid = ? AND subjectId = ?");
        $performanceCoursesStatement->execute([$userId, $row['subjectId']]);

        if ($performanceCoursesStatement->rowCount() > 0) {
            $result = $performanceCoursesStatement->fetch(PDO::FETCH_ASSOC);

            if ($result['performance_course'] == "1" || $result['performance_course'] == "2") {
                $points = $points + $row['grade'];
            }

            if ($row['grade'] < 5) {
                $lowerCourses++;
            }
        }
    }

    if ($lowerCourses > 3) {
        return 'Du hast '. $lowerCourses .' Unterkurse und damit die 3 Unterkurse die du haben darft überstritten. Du hast damit <span class="text-red">nicht bestanden</span>!';
    }


    $points = $points * 40;
    $points = $points / 44;

    $examStatement = $pdo->prepare('SELECT * FROM exams WHERE userid = ?');
    $examStatement->execute(array($userId));

    while ($row = $examStatement->fetch()) {
        $points += $row['examsGrade'];
        $points += $row['examsGrade'];
    }

    if ($points >= 300) {
        $points = round($points);
        return "Du hast mit deinen momentanen Noten $points Punkte und hast damit <span class='text-green'>bestanden</span>!";
    } else {
        $points = round($points);
        return "Du hast mit deinen momentanen Noten $points Punkte und hast damit <span class='text-red'>nicht bestanden</span>!";
    }
}

// Schließe die SQL verbindung
$pdo = null;
?>