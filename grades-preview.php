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
    <title>Notes Points Checker - Noten Übersicht</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="index.html">Startseite</a></li>
                <li><a href="compare.html">Vergleiche dich</a></li>
                <li><a href="#">Noten eintragen</a></li>
                <li><a href="login.html">Login</a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Übersicht</h1>
            <p>Hier kannst du sehen, ob du genügend Punkte hast.
            </p>

            <h1>Email: <?php echo $_POST['email']; ?></h1>

            <table>
                <thead>
                    <tr>
                        <th>Fach</th>
                        <th>Noten Punkte</th>
                        <th>Leistungsfach</th>
                    </tr>
                </thead>
                <tbody>

                <?php
            $sql = "SELECT * FROM subjects";
            foreach ($pdo->query($sql) as $row) {

                $isLK = "Nein";
                if (isset($_POST['is-lk-'. $row["id"]]))
                  if ($_POST['is-lk-'. $row["id"]])
                    $isLK = "Ja";

                echo '
                <tr>
                <td>'. $row["displayName"] .'</td>
                <td>'. $_POST[$row["id"] .'-grade'].'</td>
                <td>'. $isLK .'</td>
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

            <p>Du würdest mit diesen Noten Punkten dein Abitur <span class="text-green">bestenen</span>!</p>
            <p>Du würdest mit diesen Noten Punkten dein Abitur <span class="text-red">nicht bestenen</span>!</p>
        </div>
    </div>
</body>

</html>