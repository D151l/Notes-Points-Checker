<!DOCTYPE html>
<html>

<?php
    session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "notesPointsChecker";
        
    $pdo = new PDO('mysql:host='. $host .';dbname='. $database, $user, $password);

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Startseite</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <?php
                  include("./includes/Sidebar.php")
                ?>
        <div class="content">
            <?php
            if(isset($_SESSION['userDisplayName'])) {
                echo "<h1>Willkommen ". $_SESSION['userDisplayName'] ." auf Notes Points Checker!</h1>";
            } else {
                echo "<h1>Willkommen auf Notes Points Checker!</h1>";
            }
            ?>
            <p>Hier kannst du schauen ob deine Noten für das Abitur reichen!</p>

            Startseite
        </div>
    </div>
</body>

</html>