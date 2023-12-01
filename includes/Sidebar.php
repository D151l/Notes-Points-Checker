        <?php
        if(isset($_SESSION['userDisplayName'])) {
            $email = $_SESSION['userEmail'];
            $gravatar_size = 50; 
            $image_style = 'border-radius: 50%;';

            $gravatar_url = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email)))
                . '?s=' . $gravatar_size;
        }
        ?>
        
        <div class="sidebar">
            <ul>
                <?php
                if(isset($_SESSION['userDisplayName'])) {
                    echo '<li><img src="' . $gravatar_url . '" alt="Gravatar-Bild" style="' . $image_style . '"> <p>'. $_SESSION['userDisplayName'] .'</p></li>';
                    echo '<li><a href="logout.php">Ausloggen</a></li>';
                } else {
                    echo '<li><a href="login.php">Anmelden oder Registriren</a></li>';
                }
                ?>
                <hr>
                <li><a href="index.php">Startseite</a></li>
                <li><a href="compare.php">Vergleiche dich</a></li>
                <?php
                if(isset($_SESSION['userDisplayName'])) {
                    echo '<li><a href="enter-grades.php">Noten eintragen</a></li>';
                }
                ?>
            </ul>
        </div>