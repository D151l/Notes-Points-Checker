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

      <form action="info.php" method="post">
        <hr>
        <?php
            $sql = "SELECT * FROM subjects";
            foreach ($pdo->query($sql) as $row) {
                echo $row["displayName"]. "<br>";
                echo '<input type="number" name="'. $row["id"] .'-point"  placeholder="0-15" /><br>';
                echo '<input type="checkbox" name="'. $row["id"] .'-lk" value="true">';
                echo '<label for="'. $row["id"] .'-lk"> Dieses Fach ist bei mir ein Leistungskurs.</label><br><hr>';
            }
        ?>
        <input type="Submit" value="Absenden" />
      </form>
    </body>
</html>