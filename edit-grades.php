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

// Überprüfen ob ein Semester angegeben wurde
if (!isset($_GET['semester'])) {
    if (!isset($_POST['semester'])) {
        header("Location: grades.php");
        exit();
    } else {
        $semester = $_POST['semester'];
    }
} else {
    $semester = $_GET['semester'];
}

// Überprüfen ob das Semester gültig ist
if ($semester < 1 || $semester > 4) {
    header("Location: grades.php");
    exit();
}

// Überprüfen ob der Benutzer das Semester hat
$statementt = $pdo->prepare("SELECT * FROM grades WHERE userid = ? AND semester = ?");
$statementt->execute([$_SESSION['userid'], $semester]);

if ($statementt->rowCount() < 1) {
    header("Location: grades.php");
    exit();
}

// Das Semester mit denn Noten updaten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "SELECT * FROM subjects";
    foreach ($pdo->query($sql) as $row) {

        if (isset($_POST[$row["id"]])) {
            $sql = "UPDATE grades SET grade = :grade WHERE userid = :userid AND semester = :semester";

            $statement = $pdo->prepare($sql);

            $statement->bindParam(':grade', $_POST[$row["id"]]);
            $statement->bindParam(':userid', $_SESSION['userid']);
            $statement->bindParam(':semester', $semester);

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
            <center>
                <div class="login-form">
                    <h2>Noten eintragen</h2>
                    <form action="edit-grades.php" method="post">
                        <h3>Du bearbeitest das Semester <?php echo $semester; ?></h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Fach</th>
                                    <th>Noten Punkte</th>
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" name="semester" id="semester" value="<?php echo $semester; ?>">

                                <?php
                                // Alle Fächer aus der Datenbank holen
                                $sql = "SELECT * FROM subjects";
                                foreach ($pdo->query($sql) as $row) {

                                    // Die Note des Benutzers aus der Datenbank holen
                                    $statement = $pdo->prepare("SELECT grade FROM grades WHERE userid = ? AND semester = ? AND subjectid = ?");
                                    $statement->execute([$_SESSION['userid'], $semester, $row["id"]]);
                                    $grade = $statement->fetch();

                                    echo '
                                        <tr>
                                            <td>' . $row["displayName"] . '</td>
                                            <td><input type="number" id="' . $row["id"] . '" name="' . $row["id"] . '" min="0" max="15" value='. $grade['grade'] .' required></td>
                                        </tr>
                                    ';
                                }
                                ?>
                            </tbody>
                        </table>

                        <hr>

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