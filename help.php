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

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Hilfe</title>
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
            <h1>Willkommen im Hilfecentrum</h1>
            <p>Hier bekommst du Hilfe.</p>

            <center>
                <div class="login-form">
                    <img src="https://cdn.discordapp.com/attachments/795090945007812640/1182227688757415988/harold-hide-the-pain-meme-09.jpg" alt="Herold am Phone" class="image">
                    <h2>Kundenhotline</h2>
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