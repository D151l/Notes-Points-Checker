<!DOCTYPE html>
<html>

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
                    <form action="grades-preview.php">
                        <label for="username">Benutzername</label>
                        <center><input type="text" id="username" name="username" class="login-input" placeholder="Max Musterman" required></center>
                        <label for="email">Email</label>
                        <center><input type="email" id="email" name="email" class="login-input" placeholder="max@musterman.de" required></center>
                        <label for="password">Passwort</label>
                        <center><input type="password" id="password" name="password" class="login-input" placeholder="********" required></center>
                        <label for="password">Passwort wiederholen</label>
                        <center><input type="password" id="password" name="password" class="login-input" placeholder="********" required></center>

                            <button type="submit">Anmelden</button>
                    </form><br>
                    <a href="login.php">Ich habe doch einen Account!</a>
                </div>
            </center>
        </div>
    </div>
</body>

</html>