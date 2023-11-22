<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Notes Points Checker</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
    </head>
    <body>
        <?php
          $host = "localhost";
          $user = "root";
          $password = "";
          $database = "notesPointsChecker";
        
          $pdo = new PDO('mysql:host='. $host .';dbname='. $database, $user, $password);


        ?>
        <?php
            $sql = "SELECT * FROM subjects";
            foreach ($pdo->query($sql) as $row) {
                echo '<hr>';
                echo $row["displayName"]. "<br>";
                echo 'Deine Punkte:'. $_POST[$row["id"] .'-point'].'<br>';
                if (isset($_POST[$row["id"] .'-lk']))
                  if ($_POST[$row["id"] .'-lk'])
                    echo 'Es ist ein Leistungsfach bei dir. <br>';
            }
        ?>
    </body>
</html>