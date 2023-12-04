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
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Noten eintragen</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <?php include("./includes/Sidebar.php"); ?>
        <div class="content">
            <h1>Du willst deine Noten eintragen oder Checken?</h1>
            <p>Hier kannst du deine Daten eintragen, Checken wie viele Punkte du hast und dich mit anderen vergleichen.</p>
            <center>
                <div class="login-form">
                    <h2>Noten eintragen</h2>
                    <form action="grades-preview.php" method="post">
                        <label for="semester">Für welches Semester ist dieses Zeugnis:</label>
                        <select name="semester" id="semester" required>
                            <?php
                            for ($i = 1; $i <= 4; $i++) {
                                $statement = $pdo->prepare("SELECT * FROM grades WHERE semester = ? AND userid = ?");
                                $statement->execute([$i, $_SESSION['userid']]);
                                if ($statement->rowCount() < 1) {
                                    echo "<option value=\"$i\">Semester $i</option>";
                               }
                            }
                            ?>
                        </select>

                        <table>
                            <thead>
                                <tr>
                                    <th>Fach</th>
                                    <th>Noten Punkte</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM subjects";
                                foreach ($pdo->query($sql) as $row) {
                                    echo '
                                        <tr>
                                            <td>' . $row["displayName"] . '</td>
                                            <td><input type="number" id="' . $row["id"] . '-grade" name="' . $row["id"] . '-grade" min="0" max="15" required></td>
                                        </tr>
                                    ';
                                }
                            ?>
                            </tbody>
                        </table>

                        <hr>

                        <button type="submit">Weiter</button>
                    </form>
               </div>
            </center>
        </div>
    </div>
</body>

</html>
<?php
$pdo = null;
?>