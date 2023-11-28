<!DOCTYPE html>
<html>

<?php
    session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "notesPointsChecker";
        
    $pdo = new PDO('mysql:host='. $host .';dbname='. $database, $user, $password);
    
    $errorMassage = "";

    if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordRepeated'])) {

        $userName = $_POST['username'];
        $userEmail = $_POST['email'];
        $userPassword = $_POST['password'];
        $userPasswordRepeated = $_POST['passwordRepeated'];

        if(strlen($userName) == 0) {
            $errorMassage = "Du musst ein Benutzer Name angeben!";

        } else if(strlen($userEmail) == 0) {
            $errorMassage = "Du musst eine Email angeben!";

        } else if(strlen($userPassword) == 0) {
            $errorMassage = "Du musst ein Passwort angeben!";

        } else if ($userPassword != $userPasswordRepeated) {
            $errorMassage = "Die Passwörter mussen überein stimmen!";

        } else if(!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $errorMassage = "Bitte eine gültige E-Mail-Adresse eingeben!";

        } else {
            $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $result = $statement->execute(array('email' => $userEmail));
            $user = $statement->fetch();
        
            if($user != false) {
                $errorMassage = "Es gibt schon einen Benutzer mit dieser E-Mail-Adresse!";
            } else {
                $passwordHash = password_hash($userPassword, PASSWORD_DEFAULT);
                
                $statement = $pdo->prepare("INSERT INTO users (displayName, email, password) VALUES (:displayName, :email, :passwort)");
                $result = $statement->execute(array('displayName' => $userName, 'email' => $userEmail, 'passwort' => $passwordHash));
        
                if($result) {        
                    echo '<script language="javascript" type="text/javascript"> document.location="login.php"; </script>';
                } else {
                    $errorMassage = "Beim Abspeichern ist ein Fehler aufgetreten!";
                }
            } 
        }
    }
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Registrieren</title>
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
            <h1>Du willst dich Registrieren?</h1>
            <p>Hier kannst du dich mit deinen Daten Registrieren.</p>
            <center>
                <div class="login-form">
                    <h2>Registrieren</h2>
                    <?php
                    if ($errorMassage !== "") {
                        echo "<h3>". $errorMassage ."</h3>";
                    }
                    ?>
                    <form action="register.php" method="post">
                        <label for="username">Benutzername</label>
                        <center><input type="text" id="username" name="username" class="login-input" placeholder="Max Musterman" required></center>
                        <label for="email">Email</label>
                        <center><input type="email" id="email" name="email" class="login-input" placeholder="max@musterman.de" required></center>
                        <label for="password">Passwort</label>
                        <center><input type="password" id="password" name="password" class="login-input" placeholder="********" required></center>
                        <label for="passwordRepeated">Passwort wiederholen</label>
                        <center><input type="password" id="passwordRepeated" name="passwordRepeated" class="login-input" placeholder="********" required></center>

                        <button type="submit">Registrieren</button>
                    </form><br>
                    <a href="login.php">Ich habe doch einen Account!</a>
                </div>
            </center>
        </div>
    </div>
</body>

</html>