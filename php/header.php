<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand ml-auto" href="/">Nobel Prizes</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                    <?php 
                        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                            // Neprihlaseny pouzivatel, zobraz odkaz na Login alebo Register stranku.
                            echo '<li class="nav-item">
                                    <a class="btn btn-primary" href="php/login.php" id="login">Login</a>
                                    </li>';
                            echo '<li class="nav-item">
                                    <p>Nie ste prihlaseny</p> </li>';
                        } else {
                            // Prihlaseny pouzivatel, zobraz odkaz na zabezpecenu stranku.
                            createLogoutButton();
                            echo '<li class="nav-item"> <p>Vitaj ' . $_SESSION['user_id'] . ' </p> </li>';
                        }
                    ?>
            </ul>
        </div>
    </nav>
</header>