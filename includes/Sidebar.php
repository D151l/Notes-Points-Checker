        <div class="sidebar">
            <ul>
                <li><a href="index.php">Startseite</a></li>
                <li><a href="compare.php">Vergleiche dich</a></li>
                <?php
                if(isset($_SESSION['userDisplayName'])) {
                    echo '<li><a href="enter-grades.php">Noten eintragen</a></li>';
                    echo '<li><a href="logout.php">Ausloggen</a></li>';
                } else {
                    echo '<li><a href="login.php">Anmelden oder Registriren</a></li>';
                }
                ?>
            </ul>
        </div>