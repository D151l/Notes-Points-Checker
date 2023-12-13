<!DOCTYPE html>
<html>

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
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Noten Übersicht</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/icon.png" type="image/png">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <?php
        include("./includes/Sidebar.php")
            ?>
        <div class="content">
            <h1>Übersicht</h1>
            <p>Hier kannst du sehen, ob du genügend Punkte hast.
            </p>

            <h2>Noten für das Semester
                <?php echo $semester; ?>
            </h2>

            <table>
                <thead>
                    <tr>
                        <th>Fach</th>
                        <th>Noten Punkte</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    // Alle Fächer aus der Datenbank holen
                    $sql = "SELECT * FROM subjects";
                    foreach ($pdo->query($sql) as $row) {

                        $points = $_POST[$row["id"] . '-grade'];

                        // Die Note des Benutzers aus der Datenbank holen
                        $statement = $pdo->prepare("INSERT INTO grades (grade, subjectId, semester, userid) VALUES (?, ?, ?, ?)");
                        $statement->execute(array($points, $row["id"], $semester, $_SESSION['userid']));

                        echo '
                <tr>
                <td>' . $row["displayName"] . '</td>
                <td>' . $points . '</td>
            </tr>
                ';
                    }
                    ?>
                </tbody>
            </table>

            <br>

            <form action="enter-grades.php" method="post">
                <button type="submit">Absenden</button>
            </form>

            <br>
        </div>
    </div>
</body>

</html>

<?php
// Schließe die SQL verbindung
$pdo = null;
?>