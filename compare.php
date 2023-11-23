<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Vergleich</title>
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
            <h1>Noten Vergleich!</h1>
            <p>Hier kannst du schauen wie andere in denn Verschiedenen Fächern ist.</p>
            <p>Du kannst nach Fächern suchen.</p>

            <div class="search-container">
                <input type="text" id="search" name="search" placeholder="Suchbegriff eingeben" class="search-input">
                <button type="submit">Suchen</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Fach</th>
                        <th>Note</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Deutsch</td>
                        <td>13 Punkte</td>
                        <td>max@musterman.de</td>
                    </tr>
                    <tr>
                        <td>Mathe</td>
                        <td>2 Punkte</td>
                        <td>max@musterman.de</td>
                    </tr>
                    <tr>
                        <td>Mathe</td>
                        <td>2 Punkte</td>
                        <td>peter@musterman.de</td>
                    </tr>
                    <tr>
                        <td>Mathe</td>
                        <td>5 Punkte</td>
                        <td>peter@musterman.de</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>