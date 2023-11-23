<!DOCTYPE html>
<html>

<?php
          $host = "localhost";
          $user = "root";
          $password = "";
          $database = "notesPointsChecker";
        
          $pdo = new PDO('mysql:host='. $host .';dbname='. $database, $user, $password);


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
        <?php
          include("./includes/Sidebar.php")
        ?>
        <div class="content">
            <h1>Du willst deine Noten eintragen oder Checken?</h1>
            <p>Hier kannst du deine Daten eintragen, Checken wie viele Punkte du hast und dich mit anderen vergleichen.
            </p>
            <center>
                <div class="login-form">
                    <h2>Noten eintragen</h2>
                    <form action="grades-preview.php" method="post">

                        <label for="email">Email</label>
                        <center><input type="email" id="email" name="email" class="login-input"
                                placeholder="max@musterman.de" required>
                        </center>

                        <div class="">
                            <input type="checkbox" id="save-datar" name="save-datar">
                            <label for="save-datar">Sollen diese Daten abgespeichert werden?</label>
                        </div>

                        <?php
            $sql = "SELECT * FROM subjects";
            foreach ($pdo->query($sql) as $row) {
                echo '
                <hr><label for="grade">Deine Note für das Fach '. $row["displayName"] .'</label>
                <center><input type="number" id="'. $row["id"] .'-grade" name="'. $row["id"] .'-grade" min="0" max="15" class="login-input"
                        placeholder="0-15" required>
                </center>

                <div class="'. $row["id"] .'-lk-checkbox">
                    <input type="checkbox" id="is-lk-'. $row["id"] .'" name="is-lk-'. $row["id"] .'">
                    <label for="is-lk">Dieses Fach ich bei mir ein Leistungskurs!</label>
                </div>
                ';
            }
        ?>

                        <hr>

                        <button type="submit">Weiter</button>
                    </form>
                </div>
            </center>
        </div>
    </div>
</body>

</html>