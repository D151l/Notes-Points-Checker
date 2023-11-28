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

    if (isset($_POST['email']) && isset($_POST['password'])) {
        $userEmail = $_POST['email'];
        $userPassword = $_POST['password'];

        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array('email' => $userEmail));
        $user = $statement->fetch();
        
        if ($user !== false) {
            if (password_verify($userPassword, $user['password'])) {
            $_SESSION['userid'] = $user['id'];
            $_SESSION['userDisplayName'] = $user['displayName'];
            echo '<script language="javascript" type="text/javascript"> document.location="index.php"; </script>';
            } else {
            $errorMassage = "Es konnte kein Benutzer mit dieser E-Mail gefunden werden oder das Passwort war ungültig!1";
        }
        } else {
            $errorMassage = "Es konnte kein Benutzer mit dieser E-Mail gefunden werden oder das Passwort war ungültig!2";
        }
    }
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Login</title>
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
            <h1>Du willst dich einloggen?</h1>
            <p>Hier kannst du dich mit deinen Daten einloggen.</p>
            <center>
                <div class="login-form">
                    <h2>Login</h2>
                    <?php
                    if ($errorMassage != "") {
                        echo "<h3>". $errorMassage ."</h3>";
                    }
                    ?>
                    <form action="login.php" method="post">
                        <label for="email">Email</label>
                        <center><input type="email" id="email" name="email" class="login-input" required></center>

                        <label for="password">Passwort</label>
                        <center><input type="password" id="password" name="password" class="login-input" required></center>

                        <button type="submit">Anmelden</button>
                    </form><br>
                    <a href="forgotten-password.php">Ich habe mein Passwort vergessen!</a><br><br>
                    <a href="register.php">Ich habe noch kein Account!</a>
                </div>
            </center>
        </div>
    </div>
</body>

</html>