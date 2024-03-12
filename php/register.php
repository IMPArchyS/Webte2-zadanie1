
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title>Registrácia</title>
</head>
<body>
    <?php 
        include_once "regHeader.php";
    ?>
    <div class="container impContainer">
        <h1 class="impFontH text-center">Registrácia</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="loginForm">
            <div class="form-group my-2">
                <label class="font-weight-bold impFontW fs-5"  for="firstname">*Meno</label>
                <input type="text" class="form-control impSelect text-light" id="firstname" name="firstname" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>">
                <p id="firstnameError" class="text-danger d-none">Povinné pole</p>
            </div>
            <div class="form-group my-2">
                <label class="font-weight-bold impFontW fs-5"  for="lastname">*Priezvisko</label>
                <input type="text" class="form-control impSelect text-light" id="lastname" name="lastname" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
                <p id="lastnameError" class="text-danger d-none">Povinné pole</p>
            </div>
            <div class="form-group my-2">
                <label class="font-weight-bold impFontW fs-5"  for="email">*Email</label>
                <input type="email" class="form-control impSelect text-light" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <p id="emailError" class="text-danger d-none">Neplatný email</p>
            </div>
            <div class="form-group my-2">
                <label class="font-weight-bold impFontW fs-5"  for="password">*Heslo</label>
                <input type="password" class="form-control impSelect text-light" id="password" name="password">
                <p id="passwordError" class="text-danger d-none">Povinné pole</p>
            </div>
            <button type="submit" class="impGreenButton btn btn-primary my-2">Registrovať</button>
        </form>
        <p id="userOccupied" class="text-danger d-none">Email sa používa</p>
        <p class="my-2 text-light">Už zaregistrovaný? <a href="login.php">Prihlásiť sa</a></p>
    <script src="../js/registerLogic.js"></script>
    <?php 
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
            
            require_once "functions.php";
            require_once "../config.php";
            require_once "../dbsFunctions.php";
            require_once '../vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
            
            session_start();

            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                header("location: ../index.php");
                exit;
            }
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $firstName = $_POST["firstname"];
                $lastName = $_POST["lastname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
            
                // Fetch the user from the database
                $user = fnc\getUserByEmail($email, $dbconfig);
                $emailValid = dbs\checkRegisterByRegex($email);
                if (!$emailValid) {
                    echo "
                    <script>
                    $(function () {
                        $('#email').addClass('impError');
                        $('#userOccupied').removeClass('d-none');
                    });
                    </script>
                    ";
                    $error = "invalid email";
                }
                elseif ($user) {
                    // The user exists, show an error message
                    echo "
                    <script>
                    $(function () {
                        $('#email').addClass('impError');
                        $('#userOccupied').removeClass('d-none');
                    });
                    </script>
                    ";
                    $error = "User already exists with this email";
                } else {
                    // The user does not exist, create the user and log them in
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
                    // 2fa
                    $g2fa = new PHPGangsta_GoogleAuthenticator();
                    $userSecret = $g2fa->createSecret();
                    $codeURL = $g2fa->getQRCodeGoogleUrl($email, $userSecret);
            
                    // $qrcode = createUser($firstName, $lastName, $email, $hashedPassword, $userSecret, $codeURL);
                    $_SESSION["firstname"] = $firstName;
                    $_SESSION["lastname"] = $lastName;
                    $_SESSION["email"] = $email;
                    $_SESSION["password"] = $hashedPassword;
                    $_SESSION["2faSecret"] = $userSecret;
                    $_SESSION["2faURL"] = $codeURL;

                    $_SESSION["user_id"] = $firstName;
                    $_SESSION["regHalf"] = true;
                    
                    // Redirect to a success page
                    header("Location: auth.php");
                }
            }
    ?>
    </div>
    <?php 
        include_once "footer.php";
        ?>
</body>
</html>