<!DOCTYPE html>
<html>

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

if (!isset($_GET['email'])) {
    header('Location: compare.php');
    exit();
}

$email = $_GET['email'];

$statement = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$statement->execute(array($email));

$user = $statement->fetch();
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Vergleich</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="https://cdn.discordapp.com/attachments/795090945007812640/1182038776550277221/s-l1200.webp"
        type="image/png">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <?php
        include("./includes/Sidebar.php")
            ?>
        <div class="content">
            <h1>Du willst dich mit wehmdirekt vergleichen?</h1>
            <p>Hier kannst du es einfach.</p>

            <h2>Du vergleichst dich mit: <?php echo $user['displayName'] ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Fach</th>
                        <?php
                        for ($i = 1; $i <= 4; $i++) {
                            echo "<th>Dein Semester $i</th>";

                            echo "<th>Sein/Ihre Semester $i</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $subjectSql = "SELECT * FROM subjects";
                    foreach ($pdo->query($subjectSql) as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['displayName'] . "</td>";
                        for ($i = 1; $i <= 4; $i++) {
                            $gradeSql = "SELECT * FROM grades WHERE userid = ? AND subjectid = ? AND semester = ?";
                            $gradeStatement = $pdo->prepare($gradeSql);
                            $gradeStatement->execute(array($_SESSION['userid'], $row['id'], $i));
                            $grade = $gradeStatement->fetch();

                            $gradeSql = "SELECT * FROM grades 
                            INNER JOIN users ON grades.userid=users.id
                            WHERE userid = ? AND subjectid = ? AND semester = ?";

                            $gradeStatement = $pdo->prepare($gradeSql);
                            $gradeStatement->execute(array($user['id'], $row['id'], $i));

                            $grade2 = $gradeStatement->fetch();

                            echo "<td>" . ($grade ? $grade['grade'] : 'Keine Daten') . "</td>";
                            echo "<td>" . ($grade2 ? $grade2['grade'] : 'Keine Date') . "</td>";
                        }
                        echo "</tr>";
                    }
                    ?>

                </tbody>
            </table>
            <br>
        </div>
    </div>
</body>

</html>


<?php
// Schließe die SQL verbindung
$pdo = null;
?>