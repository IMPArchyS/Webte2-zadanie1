<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title>Authentification</title>
</head>
<body>
<?php 
    include_once "regHeader.php";

    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

    require_once "functions.php";
    require_once '../vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';

    session_start();

    function createUser($firstName, $lastName, $email, $hashedPassword, $userSecret, $codeURL) {
        // Database connection details
        $conn = createMySqlConnection();
    
        // Prepare the SQL statement
        $stmt = $conn->prepare('INSERT INTO users (first_name, last_name, email, password, 2fa_code) VALUES (?, ?, ?, ?, ?)');
    
        // Bind the parameters to the SQL statement
        $stmt->bind_param('sssss', $firstName, $lastName, $email, $hashedPassword, $userSecret);
    
        // Execute the SQL statement
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: /");
        exit;
    }

    if(!isset($_SESSION["regHalf"]) || $_SESSION["regHalf"] === false){
        header("location: login.php");
        exit;
    } else {
        $qrcode = $_SESSION["2faURL"];
        if (isset($qrcode)) {
            // Pokial bol vygenerovany QR kod po uspesnej registracii, zobraz ho.

            $message = '<div class="container">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="card mt-5">
                            <div class="card-body text-center">
                                <p class="card-text">Naskenujte QR kod do aplikacie Authenticator pre 2FA:</p>
                                <img src="'.$qrcode.'" alt="qr kod pre aplikaciu authenticator" class="img-fluid">
                                <form action="auth.php" method="post" id="form">
                                    <div class="form-group">
                                        <label for="2fa">2FA Code:</label>
                                        <input type="number" id="2fa" name="2fa" class="form-control">
                                        <p id="2faError" class="text-danger d-none">This field is required</p>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Verify</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
                            
            echo $message;
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $g2fa = new PHPGangsta_GoogleAuthenticator();
        $userSecret = $_SESSION["2faSecret"];
    
        if ($g2fa->verifyCode($userSecret, $_POST['2fa'], 2)) {
            $firstName = $_SESSION["firstname"];
            $lastName = $_SESSION["lastname"];
            $email = $_SESSION["email"];
            $password = $_SESSION["password"];
            $codeURL = $_SESSION["2faURL"];
    
            if(createUser($firstName, $lastName, $email, $password, $userSecret, $codeURL)) {
                echo "
                <script>
                $(function () {
                    $('#2fa').removeClass('impError');
                    $('#2faError').addClass('d-none');
                    $('#2faError').text('This field is required');
                });
                </script>
                ";
                $_SESSION = array();
                session_unset();
                session_start();
                $_SESSION["regHalf"] = true;
    
                header("location: login.php");
            }
        }
        else {
            echo "
            <script>
            $(function () {
                $('#2fa').addClass('impError');
                $('#2faError').removeClass('d-none');
                $('#2faError').text('Wrong credentials');
            });
            </script>
            ";
        }
    }
    include_once "footer.php";
?>
<script src="../js/authLogic.js"></script>
</body>
</html>