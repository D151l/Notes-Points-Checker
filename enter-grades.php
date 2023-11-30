<!DOCTYPE html>
<html>

<?php
    session_start();

    if (!isset($_SESSION['userDisplayName'])) {
        echo '<script language="javascript" type="text/javascript"> document.location="index.php?not-logind=1"; </script>';
    }

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
                    <label for="cars">Für welches Semester ist dieses Zügnis:</label>

                    <select name="semester" id="semester" required>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                        <option value="3">Semester 3</option>
                        <option value="4">Semester 4</option>
                    </select> 

                        <table>
                            <thead>
                                <tr>
                                    <th>Fach</th>
                                    <th>Noten Punkte</th>
                                    <th>Leistungskurs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT * FROM subjects";
                                foreach ($pdo->query($sql) as $row) {
                                    echo '
                                        <tr>
                                            <td>'. $row["displayName"] .'</td>
                                            <td><input type="number" id="'. $row["id"] .'-grade" name="'. $row["id"] .'-grade" min="0" max="15" required></td>
                                            <td><input type="checkbox" id="is-lk-'. $row["id"] .'" name="is-lk-'. $row["id"] .'"></td>
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