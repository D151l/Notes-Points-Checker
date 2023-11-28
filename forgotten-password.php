<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Passwort vergessen</title>
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
            <h1>Du hast dein Passwort vergessen?</h1>
            <p>Hier kannst du die ersten schriete einleiten um dein Passwort zurückzusetzen...</p>
            <center>
                <div class="login-form">
                    <h2>Passwort vergessen</h2>
                    <form>
                        <label for="email">Email</label>
                        <center><input type="email" id="email" name="email" class="login-input" placeholder="max@musterman.de" required>
                        </center>

                        <button type="submit">Anmelden</button>
                    </form><br>
                    <a href="login.php">Ich mein Passwort doch nicht vergessen!</a>
                </div>
            </center>
        </div>
    </div>
</body>

</html>