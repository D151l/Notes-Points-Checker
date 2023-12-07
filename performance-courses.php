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

if (isset($_POST['p1-course']) && isset($_POST['p2-course']) && isset($_POST['p3-course'])) {
    $p1 = $_POST['p1-course'];
    $p2 = $_POST['p2-course'];
    $p3 = $_POST['p3-course'];

    // Lösche bestehende Datensätze für den Benutzer
    $deleteStatement = $pdo->prepare('DELETE FROM performance_courses WHERE userid = ?');
    $deleteStatement->execute(array($_SESSION['userid']));

    // Füge neue Datensätze ein
    $insertStatement = $pdo->prepare("INSERT INTO performance_courses (performance_course, subjectId, userid) VALUES (:performance_course, :subjectId, :userid)");

    for ($i = 1; $i <= 3; $i++) {
        $result = $insertStatement->execute(array('performance_course' => $i, 'subjectId' => $_POST["p{$i}-course"], 'userid' => $_SESSION['userid']));
    }

    if ($result) {
        header("Location: grades.php");
        exit();
    } else {
        echo "Fehler beim Speichern der Leistungskurse.";
    }
}

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Leistungs Kurse</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/icon.png" type="image/png">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <?php include("./includes/Sidebar.php"); ?>
        <div class="content">
            <h1>Übersicht über deine Leistungskurse</h1>
            <p>Hier hast du eine Übersicht über deine Leistungskurse und kannst diese gegebenenfalls bearbeiten.</p>

            <center>
                <div class="login-form">
                    <h2>Leistungskurse</h2>
                    <form action="performance-courses.php" method="post">
                        <?php
                        // Generire das Formular
                        for ($i = 1; $i <= 3; $i++):
                            ?>
                            <label for="p<?= $i ?>-course">Was ist dein P
                                <?= $i ?> Fach?
                            </label>
                            <select name="p<?= $i ?>-course" id="p<?= $i ?>-course" required>
                                <?php
                                $subjectSql = "SELECT * FROM subjects";
                                foreach ($pdo->query($subjectSql) as $row) {
                                    echo '<option value="' . $row["id"] . '">' . $row["displayName"] . '</option>';
                                }
                                ?>
                            </select>
                        <?php endfor; ?>

                        <button type="submit">Speichern</button>
                    </form>
                </div>
            </center>
        </div>
    </div>
</body>

</html>

<?php
// Schließe die SQL verbindung
$pdo = null;
?>