<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title>Prihlásenie</title>
</head>
<body>
    <?php 
        include_once "regHeader.php";
        require_once '../vendor/autoload.php';
        require_once '../config.php';
        require_once 'functions.php';

        $conn = fnc\createMySqlConnection($dbconfig);
        // Inicializacia Google API klienta
        $client = new Google\Client();

        // Definica konfiguracneho JSON suboru pre autentifikaciu klienta.
        // Subor sa stiahne z Google Cloud Console v zalozke Credentials.
        $client->setAuthConfig("../client_secret.json");

        // Nastavenie URI, na ktoru Google server presmeruje poziadavku po uspesnej autentifikacii.
        $redirect_uri = "https://node51.webte.fei.stuba.sk/zad1/php/redirect.php";
        $client->setRedirectUri($redirect_uri);

        // Definovanie Scopes - rozsah dat, ktore pozadujeme od pouzivatela z jeho Google uctu.
        $client->addScope("email");
        $client->addScope("profile");

        // Vytvorenie URL pre autentifikaciu na Google server - odkaz na Google prihlasenie.
        $auth_url = $client->createAuthUrl();
    ?>

    <div class="container impContainer">
        <h1 class="impFontH text-center">Prihlásenie</h1>
        <form method="POST" action="login.php" id="loginForm">
            
            <div class="form-group my-2">
                <label class="font-weight-bold impFontW fs-5"  for="email">Email</label>
                <input type="email" class="form-control text-light impSelect" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <p id="emailError" class="text-danger d-none">Neplatný email</p>
            </div>
            <div class="form-group my-2">
                <label class="font-weight-bold impFontW fs-5"  for="password">Heslo</label>
                <input type="password" class="form-control text-light impSelect" id="password" name="password">
                <p id="passwordError" class="text-danger d-none">Povinné pole</p>
            </div>
            <div class="form-group my-2">
                <label class="font-weight-bold impFontW fs-5" for="2fa">2FA kód</label>
                <input type="number" class="form-control text-light impSelect" id="2fa" name="2fa">
                <p id="2faError" class="text-danger d-none">Povinné pole</p>
            </div>
            <button id="submitLoginButton" type="submit" class="impGreenButton my-3 btn btn-primary">Prihlásiť sa</button>
        </form>
    <p id="wrongCreds" class="text-danger d-none">Zlé údaje</p>
        <div class ="googleSign">
        <?php
        $authUrl = $client->createAuthUrl();
        echo '<a class="m-0" href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="../images/googleSign.png" class="my-3" style="width: 250px; height: 50px;"alt="fajny google login"></a>';
        ?>
    </div>
        <p class="text-light">Nový člen? <a href="register.php">Registrovať tu</a></p>
        
    </div>
    <?php 
        include_once "footer.php";
    ?>
</body>
<script src="../js/loginLogic.js"></script>
</html>

<?php 
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

require_once "functions.php";
require_once "../config.php";
require_once '../vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ../index.php");
    exit;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Fetch the user from the database
    $user = fnc\getUserByEmail($email, $dbconfig);
    
    if ($user) {
        // The user exists, verify the password
        if (password_verify($password, $user["password"])) {
            // The password is correct, log the user in
            $g2fa = new PHPGangsta_GoogleAuthenticator();

            if ($g2fa->verifyCode($user["2fa_code"], $_POST['2fa'], 2)) {
                $firstName = $user["first_name"];
                $_SESSION["user_id"] = $firstName;
                $_SESSION["loggedin"] = true;
                // Redirect to a success page
                echo "
                <script>
                $(function () {
                    $('#loginForm input').removeClass('impError');
                    $('#wrongCredentials').addClass('d-none');
                });
                </script>
                ";
                header("Location: ../index.php");
                exit;
            } else {
                echo "
                <script>
                $(function () {
                    $('#loginForm input').addClass('impError');
                    $('#wrongCredentials').addClass('d-none');
                    $('#2faError').removeClass('d-none');
                    $('#2faError').text('Wrong credentials');
                });
                </script>
                ";
            }
        } else {
            echo "
            <script>
            $(function () {
                $('#loginForm input').addClass('impError');
                $('#wrongCredentials').text('Wrong credentials');
                $('#wrongCredentials').removeClass('d-none');
            });
            </script>
            ";
            $error = "Incorrect password";
        }
    } else {
        // The user does not exist, show an error message
        echo "
        <script>
        $(function () {
            $('#wrongCredentials').text('User does not exist');
            $('#wrongCredentials').removeClass('d-none');
        });
        </script>
        ";
        $error = "User does not exist";
    }
}