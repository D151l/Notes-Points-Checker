<!DOCTYPE html>
<html>

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
                    <form>
                        <label for="username">Benutzername oder Email</label>
                        <center><input type="text" id="username" name="username" class="login-input" required></center>

                        <label for="password">Passwort</label>
                        <center><input type="password" id="password" name="password" class="login-input" required></center>

                        <button type="submit">Anmelden</button>
                    </form><br>
                    <a href="forgotten-password.html">Ich habe mein Passwort vergessen!</a><br><br>
                    <a href="register.html">Ich habe noch kein Account!</a>
                </div>
            </center>
        </div>
    </div>
</body>

</html>