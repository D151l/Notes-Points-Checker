<!DOCTYPE html>
<html>

<?php
    session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "notesPointsChecker";
        
    $pdo = new PDO('mysql:host='. $host .';dbname='. $database, $user, $password);

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
                    <input type="text" id="search" name="search" placeholder="Suchbegriff eingeben" class="search-input">
                    <button type="submit">Suchen</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Fach</th>
                        <th>Noten Punkte</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                <?php

                if (isset($_GET['search'])) {
                    $search = $_GET['search'];

                    $sql = 'SELECT grades.grade, grades.email, subjects.displayName 
                            FROM grades 
                            INNER JOIN subjects ON grades.subjectId = subjects.id 
                            WHERE grades.email LIKE :search 
                                OR grades.email LIKE :searchWildcard 
                                OR grades.email LIKE :searchWildcardEnd
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
                                    <td>' . $row["email"] . '</td>
                                </tr>';
                        }
                    } else {
                        $sql = 'SELECT grades.grade, grades.email, subjects.displayName 
                                FROM grades 
                                INNER JOIN subjects ON grades.subjectId = subjects.id 
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
                                    <td>' . $row["email"] . '</td>
                                </tr>';
                        }
                    }
                
                 } else {
                    $sql = "SELECT grades.grade, grades.email, subjects.displayName 
                            FROM grades 
                            INNER JOIN subjects ON grades.subjectId=subjects.id
                            LIMIT :maxRows;";
                    $statement = $pdo->prepare($sql);
                    $statement->bindParam(':maxRows', $maxRows, PDO::PARAM_INT);
                    $statement->execute();
                     while ($row = $statement->fetch()) {
                        echo '
                            <tr>
                                <td>' . $row["displayName"] . '</td>
                                 <td>' . $row["grade"] . '</td>
                                 <td>' . $row["email"] . '</td>
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
                        echo '<input type="text" id="search" name="search" value="'. $search .'" hidden>';
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