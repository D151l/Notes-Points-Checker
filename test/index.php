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

          $statement = $pdo->prepare("SELECT * FROM subjects");
          $statement->execute(); 
          $anzahl_user = $statement->rowCount();
          echo "Es wurden $anzahl_user Datensätze gefunden!<br>";

          $sql = "SELECT * FROM subjects";
          foreach ($pdo->query($sql) as $row) {
            echo  $row["id"] ." - ".$row["displayName"]. "<br>";
          }
        ?>
    </body>
</html>