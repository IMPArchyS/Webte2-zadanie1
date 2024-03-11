<header>
    <nav class="navbar navbar-light bg-light">
        <a id="mainPageButton" class="navbar-brand ml-auto" href="../index.php">Nobelove Ceny</a>
        <div class="mx-4" id="navbarNav">
            <ul class="navbar-nav ml-auto d-flex flex-row justify-content-center align-items-center ">
                    <?php 
                        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                            // Neprihlaseny pouzivatel, zobraz odkaz na Login alebo Register stranku.
                            $current_page = basename($_SERVER['PHP_SELF']);
                            echo '<li class="nav-item"> <h5 class="text-center mb-0 mx-4">Hosť</h5> </li>';
                            if ($current_page == 'person.php') {
                            echo '<li class="nav-item">
                                    <a class="btn btn-primary" href="login.php" id="login">Prihlásiť sa</a>
                                    </li>';
                            } else {
                                echo '<li class="nav-item">
                                <a class="btn btn-primary" href="php/login.php" id="login">Prihlásiť sa</a>
                                </li>';
                            }
                        } else {
                            // Prihlaseny pouzivatel, zobraz odkaz na zabezpecenu stranku.
                            echo '<li class="nav-item"> <h5 class="text-center mb-0 mx-4">Vitajte ' . $_SESSION['user_id'] . ' </h5> </li>';
                            createLogoutButton();   
                        }
                    ?>
            </ul>
        </div>
    </nav>
</header>