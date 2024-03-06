
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title>Register</title>
</head>
<body>
    <?php 
        include_once "regHeader.php";
    ?>
    <div class="container">
        <h1>Register</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="loginForm">
            <div class="form-group">
                <label for="firstname">*First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : ''; ?>">
                <p id="firstnameError" class="text-danger d-none">This field is required</p>
            </div>
            <div class="form-group">
                <label for="lastname">*Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
                <p id="lastnameError" class="text-danger d-none">This field is required</p>
            </div>
            <div class="form-group">
                <label for="email">*Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <p id="emailError" class="text-danger d-none">Invalid email</p>
            </div>
            <div class="form-group">
                <label for="password">*Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <p id="passwordError" class="text-danger d-none">This field is required</p>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p id="userOccupied" class="text-danger d-none">Email is already in use</p>
        <p>Already registered? <a href="login.php">Login here</a></p>
    <script src="../js/registerLogic.js"></script>
    <?php 
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
            
            require_once "functions.php";
            require_once '../vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
            
            function createUser($firstName, $lastName, $email, $hashedPassword, $userSecret, $codeURL) {
                // Database connection details
                $conn = createMySqlConnection();
            
                // Prepare the SQL statement
                $stmt = $conn->prepare('INSERT INTO users (first_name, last_name, email, password, 2fa_code) VALUES (?, ?, ?, ?, ?)');
            
                // Bind the parameters to the SQL statement
                $stmt->bind_param('sssss', $firstName, $lastName, $email, $hashedPassword, $userSecret);
            
                // Execute the SQL statement
                if ($stmt->execute()) {
                    $qrcode = $codeURL;
                    return $qrcode;
                } else {
                    echo "error";
                }
            }
            
            function getUserByEmail($email) {
                // Database connection details
                $conn = createMySqlConnection();
            
                // Prepare the SQL statement
                $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
            
                // Bind the email to the SQL statement
                $stmt->bind_param('s', $email);
            
                // Execute the SQL statement
                $stmt->execute();
            
                // Get the result of the query
                $result = $stmt->get_result();
            
                // Fetch the user data
                $user = $result->fetch_assoc();
            
                return $user;
            }
            
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                header("location: /");
                exit;
            }
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $firstName = $_POST["firstname"];
                $lastName = $_POST["lastname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
            
                // Fetch the user from the database
                $user = getUserByEmail($email);
            
                if ($user) {
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
            
                    $qrcode = createUser($firstName, $lastName, $email, $hashedPassword, $userSecret, $codeURL);
                    
                    $_SESSION["user_id"] = $firstName;
                    //$_SESSION["loggedin"] = true;
                    
                    // Redirect to a success page
                    //header("Location: /");
                }
            }
            
            if (isset($qrcode)) {
                // Pokial bol vygenerovany QR kod po uspesnej registracii, zobraz ho.
                $message = '<p>Naskenujte QR kod do aplikacie Authenticator pre 2FA: <br><img src="'.$qrcode.'" alt="qr kod pre aplikaciu authenticator"></p>';
                
                echo $message;
                echo '<p>Teraz sa mozte prihlasit: <a href="login.php" role="button">Login</a></p>';
            }
    ?>
    </div>
    <?php 
        include_once "footer.php";
        ?>
</body>
</html>