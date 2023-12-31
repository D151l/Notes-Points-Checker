<!DOCTYPE html>
<html>

<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "notesPointsChecker";

// Verbinde zu Datenbank
$pdo = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$maxRows = 10;
if (isset($_GET['show-more']))
    $maxRows = $_GET['show-more'];
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Notes Points Checker - Vergleich</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/icon.png" type="image/png">
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
                <form action="compare.php">
                    <input type="text" id="search" name="search" placeholder="Suchbegriff eingeben"
                        class="search-input">
                    <button type="submit">Suchen</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Fach</th>
                        <th>Noten Punkte</th>
                        <th>Semester</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    //Überprüfe ob nach etwas gesucht wird
                    if (isset($_GET['search'])) {
                        $search = $_GET['search'];

                        //Überprüfe ob nach einer Email gesucht wird
                        $sql = 'SELECT grades.grade, grades.semester, users.email, .subjects.displayName 
                            FROM grades 
                            INNER JOIN subjects ON grades.subjectId=subjects.id
                            INNER JOIN users ON grades.userid=users.id
                            WHERE users.email LIKE :search 
                                OR users.email LIKE :searchWildcard 
                                OR users.email LIKE :searchWildcardEnd
                                LIMIT :maxRows';
                        $statement = $pdo->prepare($sql);
                        $searchTerm = '%' . $search . '%';
                        $statement->bindParam(':search', $searchTerm, PDO::PARAM_STR);
                        $statement->bindParam(':searchWildcard', $searchTerm, PDO::PARAM_STR);
                        $statement->bindParam(':searchWildcardEnd', $search, PDO::PARAM_STR);
                        $statement->bindParam(':maxRows', $maxRows, PDO::PARAM_INT);
                        $statement->execute();

                        $rows = $statement->rowCount();

                        if ($rows > 0) {
                            while ($row = $statement->fetch()) {
                                echo '
                                <tr>
                                    <td>' . $row["displayName"] . '</td>
                                    <td>' . $row["grade"] . '</td>
                                    <td>' . $row["semester"] . '</td>
                                 <td><a href="compare-dyrect.php?email=' . $row["email"] . '">' . $row["email"] . '</a></td>
                                </tr>';
                            }
                        } else {
                            //Überprüfe ob nach einem Fach gesucht wird
                            $sql = 'SELECT grades.grade, grades.semester, users.email, .subjects.displayName 
                                FROM grades 
                                INNER JOIN subjects ON grades.subjectId=subjects.id
                                INNER JOIN users ON grades.userid=users.id
                                WHERE subjects.displayName  LIKE :search 
                                    OR subjects.displayName LIKE :searchWildcard 
                                    OR subjects.displayName LIKE :searchWildcardEnd
                                    OR subjects.id  LIKE :search 
                                    OR subjects.id LIKE :searchWildcard 
                                    OR subjects.id LIKE :searchWildcardEnd
                                    LIMIT :maxRows';
                            $statement = $pdo->prepare($sql);
                            $searchTerm = '%' . $search . '%';
                            $statement->bindParam(':search', $searchTerm, PDO::PARAM_STR);
                            $statement->bindParam(':searchWildcard', $searchTerm, PDO::PARAM_STR);
                            $statement->bindParam(':searchWildcardEnd', $search, PDO::PARAM_STR);
                            $statement->bindParam(':maxRows', $maxRows, PDO::PARAM_INT);
                            $statement->execute();

                            while ($row = $statement->fetch()) {
                                echo '
                                <tr>
                                    <td>' . $row["displayName"] . '</td>
                                    <td>' . $row["grade"] . '</td>
                                    <td>' . $row["semester"] . '</td>
                                 <td><a href="compare-dyrect.php?email=' . $row["email"] . '">' . $row["email"] . '</a></td>
                                </tr>';
                            }
                        }

                    } else {
                        //Wenn nicht nach etwas gesucht wird, zeige die ersten 10 Einträge an
                        $sql = "SELECT grades.grade, grades.semester, users.email, .subjects.displayName 
                            FROM grades 
                            INNER JOIN subjects ON grades.subjectId=subjects.id
                            INNER JOIN users ON grades.userid=users.id
                            LIMIT :maxRows;";
                        $statement = $pdo->prepare($sql);
                        $statement->bindParam(':maxRows', $maxRows, PDO::PARAM_INT);
                        $statement->execute();
                        while ($row = $statement->fetch()) {
                            echo '
                            <tr>
                                <td>' . $row["displayName"] . '</td>
                                 <td>' . $row["grade"] . '</td>
                                 <td>' . $row["semester"] . '</td>
                                 <td><a href="compare-dyrect.php?email=' . $row["email"] . '">' . $row["email"] . '</a></td>
                            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>

            <br>

            <div class="show-more-container">
                <form action="compare.php">
                    <?php
                    if (isset($_GET['search'])) {
                        echo '<input type="text" id="search" name="search" value="' . $search . '" hidden>';
                    }
                    ?>
                    <input type="number" id="show-more" name="show-more" value=<?php echo $maxRows + 10; ?> hidden>
                    <button type="submit">Mehr Anzeigen!</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<?php
// Schließe die SQL verbindung
$pdo = null;
?>