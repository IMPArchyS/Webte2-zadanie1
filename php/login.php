<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title>Login</title>
</head>
<body>
    <?php 
        include_once "regHeader.php";
    ?>

    <div class="container">
        <h1>Login</h1>
        <form method="POST" action="login.php" id="loginForm">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <p id="emailError" class="text-danger d-none">Invalid email</p>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <p id="passwordError" class="text-danger d-none">This field is required</p>
            </div>
            <div class="form-group">
                <label for="2fa">2FA Code</label>
                <input type="number" class="form-control" id="2fa" name="2fa">
                <p id="2faError" class="text-danger d-none">This field is required</p>
            </div>
            <button id="submitLoginButton" type="submit" class="btn btn-primary">Login</button>
        </form>
        <p id="wrongCredentials" class="text-danger d-none">Wrong credentials</p>
        <p>Not registered? <a href="register.php">Register here</a></p>
        
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
require_once '../vendor/phpgangsta/googleauthenticator/PHPGangsta/GoogleAuthenticator.php';
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: /");
    exit;
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Fetch the user from the database
    $user = getUserByEmail($email);
    
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